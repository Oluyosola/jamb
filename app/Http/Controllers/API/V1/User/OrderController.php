<?php

namespace App\Http\Controllers\API\V1\User;

use App\Actions\GenerateUniqueIdAction;
use App\Actions\Transaction\SaveTransactionAction;
use App\Enums\PaymentGateway;
use App\Enums\PaymentPurpose;
use App\Enums\SystemConfigEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\User\Order\StoreOrderRequest;
use App\Http\Requests\API\V1\User\Order\UpdateOrderRequest;
use App\Models\Artisan;
use App\Models\Order;
use App\Notifications\Artisan\ArtisanCalledOutNotification;
use App\Services\OrderService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MarcinOrlowski\ResponseBuilder\ResponseBuilder;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    /**
     * Inject dependencies.
     *
     * @param UserService $userService
     * @param TransactionService $transactionService
     * @return void
     */
    public function __construct(public UserService $userService, public OrderService $orderService)
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function index(Request $request)
    {
        $order = QueryBuilder::for($this->orderService->index())
            ->allowedFilters([
                'artisan_id',
                'user_id',
            ])
            ->allowedIncludes([
                'artisan',
                'user',
            ])
            ->defaultSort('-updated_at')->paginate($request->per_page);

        return ResponseBuilder::asSuccess()
            ->withData(['Orders' => $order])
            ->withMessage('Orders fetched successfully.')
            ->build();
    }

    /**
     * Store specified resource in storage.
     *
     * @param StoreOrderRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store(StoreOrderRequest $request)
    {
        $user = $request->user();

        abort_if(
            !$this->userService->hasPaidLocationCallOutCharge($user, $request->location),
            \Symfony\Component\HttpFoundation\Response::HTTP_BAD_REQUEST,
            'No callout fee paid.'
        );
        $order = $this->orderService->store($request);
        $artisan = Artisan::with(['association'])->find($request->artisan_id, [
            'id',
            'email',
            'phone',
            'address',
            'association_id',
        ]);
        $artisan->notify(new ArtisanCalledOutNotification());

        return ResponseBuilder::asSuccess()
            ->withHttpCode(Response::HTTP_CREATED)
            ->withMessage('Order created successfully')
            ->withData([
                'order' => $order,
                'artisan' => $artisan
            ])
            ->build();
    }

    public function show($orderid)
    {
        $order = QueryBuilder::for(Order::where('id', $orderid))
            ->allowedIncludes([
                'artisan',
                'user',
            ])
            ->findOrFail($orderid);
        return ResponseBuilder::asSuccess()
            ->withData(['order' => $order])
            ->withMessage('Order fetched successfully.')
            ->build();
    }



    /**
     * Pay a callout charge via wallet.
     *
     * @param StoreOrderRequest $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function payCalloutChargeViaWallet(StoreOrderRequest $request)
    {
        try {
            DB::beginTransaction();

            $user = $request->user();
            $array['amount'] = SystemConfigEnum::CALLOUTCHARGEFEE;
            $array['payment_purpose'] = PaymentPurpose::CALLOUTCHARGE;
            $artisan = Artisan::find($request->artisan_id, [
                'id',
                'email',
                'phone',
                'address',
            ]);

            // create the order.
            $order = $this->orderService->store($request);
            $array['metadata']['package_type'] = get_class($order) ?? null;
            $array['metadata']['package_id'] = $order->id ?? null;
            $array['user'] = $request->user();

            $user->chargeWallet($artisan, $array);

            // Send mail notification to artisan
            $artisan->notify(new ArtisanCalledOutNotification());

            DB::commit();

            return ResponseBuilder::asSuccess()
                ->withHttpCode(Response::HTTP_CREATED)
                ->withMessage('Order created successfully')
                ->withData(['artisan' => $artisan])
                ->build();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
}

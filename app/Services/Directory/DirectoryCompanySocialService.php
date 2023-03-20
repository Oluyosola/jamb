<?php

namespace App\Services\Directory;

use App\Http\Requests\API\V1\Admin\DirectoryCompany\Social\StoreDirectoryCompanySocialRequest;
use App\Http\Requests\API\V1\Admin\DirectoryCompany\Social\UpdateDirectoryCompanySocialRequest;
use App\Models\DirectoryCompany;
use App\Models\DirectoryCompanySocial;

class DirectoryCompanySocialService
{
    /**
     * Fetch all directory company socials.
     *
     * @param DirectoryCompany $directoryCompany
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function index(DirectoryCompany $directoryCompany)
    {
        return $directoryCompany->socialAccount();
    }

    /**
     * Store a new directory company social in storage.
     *
     * @param StoreDirectoryCompanySocialRequest $request
     * @param DirectoryCompany $directoryCompany
     * @return DirectoryCompanySocial $socialAccount
     */
    public function store(StoreDirectoryCompanySocialRequest $request, DirectoryCompany $directoryCompany)
    {
        $socialAccount = new DirectoryCompanySocial();
        $socialAccount->directoryCompany()->associate($directoryCompany);
        $socialAccount->facebook_url = $request->facebook_url;
        $socialAccount->twitter_url = $request->twitter_url;
        $socialAccount->instagram_url = $request->instagram_url;
        $socialAccount->yookos_url = $request->yookos_url;
        $socialAccount->linkedin_url = $request->linkedin_url;
        $socialAccount->tiktok_url = $request->tiktok_url;
        $socialAccount->skype_url = $request->skype_url;
        $socialAccount->youtube_url = $request->youtube_url;
        $socialAccount->save();

        return $socialAccount;
    }

    /**
     * Update a directory company social in storage.
     *
     * @param UpdateDirectoryCompanySocialRequest $request
     * @param DirectoryCompany $directoryCompany
     * @param DirectoryCompanySocial $directoryCompanySocial
     * @return DirectoryCompanySocial $directoryCompanySocial
     */
    public function update(
        UpdateDirectoryCompanySocialRequest $request,
        DirectoryCompany $directoryCompany,
        DirectoryCompanySocial $directoryCompanySocial
    ) {
        $directoryCompanySocial->directoryCompany()->associate($directoryCompany);
        $directoryCompanySocial->facebook_url = $request->facebook_url;
        $directoryCompanySocial->twitter_url = $request->twitter_url;
        $directoryCompanySocial->instagram_url = $request->instagram_url;
        $directoryCompanySocial->yookos_url = $request->yookos_url;
        $directoryCompanySocial->linkedin_url = $request->linkedin_url;
        $directoryCompanySocial->tiktok_url = $request->tiktok_url;
        $directoryCompanySocial->skype_url = $request->skype_url;
        $directoryCompanySocial->youtube_url = $request->youtube_url;
        $directoryCompanySocial->update();
        return $directoryCompanySocial;
    }

    /**
     * Delete the specified directory company social.
     *
     * @param DirectoryCompanySocial $directoryCompanySocial
     * @return bool
     */
    public function destroy(DirectoryCompanySocial $directoryCompanySocial): bool
    {
        return $directoryCompanySocial->delete() ? true : false;
    }
}

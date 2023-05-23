<?php

namespace MicroweberPackages\Livewire\Auth\Access;


trait AuthorizesAdminRequests
{
    /**
     * Authorize a given action for the current user.
     *
     * @param mixed $ability
     * @param mixed|array $arguments
     * @return \Illuminate\Auth\Access\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function authorize($ability, $arguments = [])
    {
        if (is_admin()) {
            return true;
        }

        return false;
    }
}

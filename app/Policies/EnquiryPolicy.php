<?php

namespace App\Policies;

use App\Models\Enquiry;
use App\Models\User;

/**
 * Enquiries are written by anonymous visitors and read only by admins — the
 * same shape as the original `enquiries_admin_all` policy, which granted admins
 * everything and left inserts to the service-role Edge Function.
 *
 * Creation is deliberately absent: it is a public action and is guarded by rate
 * limiting and a honeypot in the controller, not by authorization.
 */
class EnquiryPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Enquiry $enquiry): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Enquiry $enquiry): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Enquiry $enquiry): bool
    {
        return $user->isAdmin();
    }
}

<?php

namespace App\Policies;

use App\Models\Author;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuthorPolicy
{
    use HandlesAuthorization;

    /**
     * @param User $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * @param User $user
     * @param Author $author
     * @return bool
     */
    public function view(User $user, Author $author): bool
    {
        return true;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * @param User $user
     * @param Author $author
     * @return bool
     */
    public function update(User $user, Author $author): bool
    {
        return true;
    }

    /**
     * @param User $user
     * @param Author $author
     * @return bool
     */
    public function delete(User $user, Author $author): bool
    {
        return true;
    }

    /**
     * @param User $user
     * @param Author $author
     * @return bool
     */
    public function restore(User $user, Author $author)
    {
        return true;
    }

    /**
     * @param User $user
     * @param Author $author
     * @return bool
     */
    public function forceDelete(User $user, Author $author)
    {
        return true;
    }
}

<?php

namespace Modules\User\Repositories\Eloquent;

use Modules\Core\Repositories\Eloquent\EloquentBaseRepository;
use Modules\User\Repositories\UserTokenRepository;
use Ramsey\Uuid\Uuid;

class EloquentUserTokenRepository extends EloquentBaseRepository implements UserTokenRepository
{
    /**
     * Get all tokens for the given user
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allForUser($userId)
    {
        return $this->model->where('user_id', $userId)->get();
    }

    /**
     * @param int $userId
     * @return \Modules\User\Entities\UserToken
     */
    public function generateFor($userId)
    {
        $uuid4 = Uuid::uuid4();

        return $this->model->create(['user_id' => $userId, 'access_token' => $uuid4]);
    }
}

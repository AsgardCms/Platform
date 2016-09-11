<?php

namespace Modules\User\Repositories\Eloquent;

use Illuminate\Database\QueryException;
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
        try {
            $uuid4 = Uuid::uuid4();
            $userToken = $this->model->create(['user_id' => $userId, 'access_token' => $uuid4]);
        } catch (QueryException $e) {
            $this->generateFor($userId);
        }

        return $userToken;
    }
}

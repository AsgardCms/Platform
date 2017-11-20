<?php

namespace Modules\User\Repositories\Cache;

use Modules\Core\Repositories\Cache\BaseCacheDecorator;
use Modules\User\Repositories\UserTokenRepository;

class CacheUserTokenDecorator extends BaseCacheDecorator implements UserTokenRepository
{
    /**
     * @var UserTokenRepository
     */
    protected $repository;

    public function __construct(UserTokenRepository $repository)
    {
        parent::__construct();
        $this->entityName = 'user-tokens';
        $this->repository = $repository;
    }

    /**
     * Get all tokens for the given user
     * @param int $userId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function allForUser($userId)
    {
        $this->remember(function () use ($userId) {
            return $this->repository->allForUser($userId);
        });
    }

    /**
     * @param int $userId
     * @return \Modules\User\Entities\UserToken
     */
    public function generateFor($userId)
    {
        $this->clearCache();

        return $this->repository->generateFor($userId);
    }
}

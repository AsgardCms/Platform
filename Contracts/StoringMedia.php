<?php

namespace Modules\Media\Contracts;

interface StoringMedia
{
    /**
     * Return the entity
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getEntity();

    /**
     * Return the ALL data sent
     * @return array
     */
    public function getSubmissionData();
}

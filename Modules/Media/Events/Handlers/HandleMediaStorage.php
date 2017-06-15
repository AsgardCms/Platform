<?php

namespace Modules\Media\Events\Handlers;

use Modules\Media\Contracts\StoringMedia;

class HandleMediaStorage
{
    public function handle($event = null, $data = [])
    {
        if ($event instanceof StoringMedia) {
            $this->handleMultiMedia($event);

            $this->handleSingleMedia($event);
        }
    }

    /**
     * Handle the request for the multi media partial
     * @param StoringMedia $event
     */
    private function handleMultiMedia(StoringMedia $event)
    {
        $entity = $event->getEntity();
        $postMedias = array_get($event->getSubmissionData(), 'medias_multi', []);

        foreach ($postMedias as $zone => $attributes) {
            $syncList = [];
            $orders = $this->getOrdersFrom($attributes);
            foreach (array_get($attributes, 'files', []) as $fileId) {
                $syncList[$fileId] = [];
                $syncList[$fileId]['imageable_type'] = get_class($entity);
                $syncList[$fileId]['zone'] = $zone;
                $syncList[$fileId]['order'] = (int) array_search($fileId, $orders);
            }
            $entity->filesByZone($zone)->sync($syncList);
        }
    }

    /**
     * Handle the request to parse single media partials
     * @param StoringMedia $event
     */
    private function handleSingleMedia(StoringMedia $event)
    {
        $entity = $event->getEntity();
        $postMedia = array_get($event->getSubmissionData(), 'medias_single', []);

        foreach ($postMedia as $zone => $fileId) {
            if (!empty($fileId)) {
                $entity->filesByZone($zone)->sync([$fileId => ['imageable_type' => get_class($entity), 'zone' => $zone, 'order' => null]]);
            } else {
                $entity->filesByZone($zone)->sync([]);
            }
        }
    }

    /**
     * Parse the orders input and return an array of file ids, in order
     * @param array $attributes
     * @return array
     */
    private function getOrdersFrom(array $attributes)
    {
        $orderString = array_get($attributes, 'orders');

        if ($orderString === null) {
            return [];
        }

        $orders = explode(',', $orderString);

        return array_filter($orders);
    }
}

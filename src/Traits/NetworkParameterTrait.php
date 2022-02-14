<?php

namespace App\Traits;

use App\Entity\Network;

trait NetworkParameterTrait
{
    protected Network $network;

    protected function getNetworkId(): int
    {
        $networkId = $this->getContext()->getRequest()->get('networkId');
        if (!$networkId) {
            $query = parse_url($this->getContext()->getRequest()->get('referrer'), PHP_URL_QUERY);
            parse_str($query, $array);

            return (int)($array['networkId']);
        }
        return $this->getContext()->getRequest()->get('networkId');
    }

    protected function getNetwork(): Network
    {
        $network = $this->networkRepository->findOneByIdAndUser($this->getNetworkId(), $this->getUser());
        if (!$network) {
            throw new \Exception('Network not found');
        }

        return $network;
    }

    protected function isGranted(mixed $attribute, mixed $subject = null): bool
    {
        $isGraneted = parent::isGranted($attribute, $subject);
        if (!$isGraneted) {
            return false;
        }
        $this->network = $this->getNetwork();

        return $this->network->getOwner()->getId() === $this->getUser()->getId();
    }

}

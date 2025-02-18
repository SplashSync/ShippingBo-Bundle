<?php

/*
 *  This file is part of SplashSync Project.
 *
 *  Copyright (C) Splash Sync  <www.splashsync.com>
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace Splash\Connectors\ShippingBo\Dictionary;

class SupplyCapsuleState
{
    public const UPLOADING = 'uploading';

    public const DRAFT = 'draft';

    public const WAITING = 'waiting';

    public const CANCELED = 'canceled';

    public const RECEIVED = 'received';

    public const DISPATCHED = 'dispatched';

    public const ERROR = 'in_trouble';
    public const ON_GOING = 'ongoing';

    public const SENT_TO_LOGISTICS = 'sent_to_logistics';

    public const ALL = array(
        self::UPLOADING,
        self::DRAFT,
        self::WAITING,
        self::CANCELED,
        self::RECEIVED,
        self::DISPATCHED,
        self::ERROR,
        self::ON_GOING,
        self::SENT_TO_LOGISTICS,
    );

    public const CHOICES = array(
        self::UPLOADING => "Upload in progress",
        self::DRAFT => "Draft",
        self::WAITING => "Waiting for pickup",
        self::CANCELED => "Canceled",
        self::RECEIVED => "Received",
        self::DISPATCHED => "Dispatched",
        self::ERROR => "In trouble",
        self::ON_GOING => "On going",
        self::SENT_TO_LOGISTICS => "Sent to logistics",
    );
}

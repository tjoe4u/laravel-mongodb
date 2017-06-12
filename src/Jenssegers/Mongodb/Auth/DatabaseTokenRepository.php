<?php namespace Jenssegers\Mongodb\Auth;

use DateTime;
use DateTimeZone;
use Illuminate\Auth\Passwords\DatabaseTokenRepository as BaseDatabaseTokenRepository;
use MongoDB\BSON\UTCDateTime;

class DatabaseTokenRepository extends BaseDatabaseTokenRepository
{

    /**
     * @inheritdoc
     */
    protected function tokenExpired($created_at)
    {
        // Convert UTCDateTime to a date string.
        if ($created_at instanceof UTCDateTime) {
            $date = $created_at->toDateTime();
            $date->setTimezone(new DateTimeZone(date_default_timezone_get()));
            $created_at = $date->format('Y-m-d H:i:s');
        } elseif (is_array($created_at) and isset($created_at['date'])) {
            $date = new DateTime($created_at['date'], new DateTimeZone(isset($created_at['timezone']) ? $created_at['timezone'] : 'UTC'));
            $date->setTimezone(new DateTimeZone(date_default_timezone_get()));
            $created_at = $date->format('Y-m-d H:i:s');
        }

        return parent::tokenExpired($created_at);
    }
}

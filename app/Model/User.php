<?php

namespace App\Model;

use App\Db;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    public $timestamps = false;

    protected $fillable = [
        'fio',
        'password',
        'email',
        'created_at',
    ];

    public static function getByEmail(string $email)
    {
        return self::query()->where('email', '=', $email)->first();
    }

    public static function getById(int $id)
    {
        return self::query()->find($id);
    }

    public static function getList(int $limit = 10, int $offset = 0)
    {
        return self::query()->limit($limit)->offset($offset)->orderBy('id', 'DESC')->get();
    }

    public static function getPasswordHash(string $password)
    {
        return sha1('.,f.akjsduf' . $password);
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->fio;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function isAdmin(): bool
    {
        return in_array($this->id, ADMIN_IDS);
    }
}
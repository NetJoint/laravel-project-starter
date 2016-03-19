<?php

namespace App\Models;

use Cartalyst\Sentinel\Users\EloquentUser as SentinelEloquentUser;
use App\Models\Traits\Listable;

class User extends SentinelEloquentUser
{

    use Listable;

    protected $fillable = [
        'name',
        'mobile', 
        'email',
        'password'
    ];
    protected $guarded = [
        'id', 'created_at'
    ];
    protected $loginNames = [ 'mobile', 'email'];
    protected $columns = ['id', 'name', 'email', 'mobile', 'password', 'permissions', 'last_login', 'created_at', 'updated_at'];
    
    /**
     * Checks if the user has a role by its slug.
     *
     * @param string|array $name       Role name or array of role names.
     * @param bool         $requireAll All roles in the array are required.
     *
     * @return bool
     */
    public function hasRole($name, $requireAll = false)
    {
        if (is_array($name)) {
            foreach ($name as $roleName) {
                $hasRole = $this->hasRole($roleName);

                if ($hasRole && !$requireAll) {
                    return true;
                } elseif (!$hasRole && $requireAll) {
                    return false;
                }
            }
            return $requireAll;
        } else {
            foreach ($this->roles as $role) {
                if ($role->slug == $name) {
                    return true;
                }
            }
        }
        return false;
    }

}

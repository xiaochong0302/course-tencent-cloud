<?php

namespace App\Providers;

use Phalcon\Config;
use Phalcon\Acl\Role as AclRole;
use Phalcon\Acl\Resource as AclResource;
use Phalcon\Acl\Adapter\Memory as MemoryAcl;

class Acl extends Provider
{
    protected $serviceName = 'acl';

    private $acl_role = [
        'guest',
        'moderator',
        'admin',
        'developer',
    ];

    public function register()
    {
        /**
         * set ACL for admin module only. So that moderator can only operate CMS part.
         */
        $resources =  require config_path('acl.php');
        $this->di->setShared($this->serviceName, function () use($resources) {
            $acl = new MemoryAcl();
            $acl->setDefaultAction(\Phalcon\Acl::DENY);
            $roles = [];
            foreach ( $this->acl_role as $order => $role ) {
                $roles[$role]  = new AclRole($role, ucfirst($role));
                if ($order == 0) {
                    $acl->addRole($roles[$role]);
                } else {
                    $acl->addRole($roles[$role], $roles[$this->acl_role[$order - 1]]);
                }

                if (!empty($resources[$role])) {
                    foreach ($resources[$role] as $module => $controller_action) {
                        if (is_array($controller_action)) {
                            foreach ($controller_action as $controller => $actions) {
                                $resource = $module . '.' . $controller;
                                $acl->addResource(new AclResource($resource), $actions);
                                $acl->allow($role, $resource, $actions);
                            }
                        } else {
                            $acl->addResource(new AclResource($module), $controller_action);
                            $acl->allow($role, $module, $controller_action);
                        }
                    }
                }
            }
            return $acl;
        });
    }
}
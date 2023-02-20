<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    function __construct(){
        $this->dashboard = ['dashboard-index'];

        $this->permission = ['permission-index', 'permission-create','permission-edit','permission-delete','permission-show'];

        $this->role = ['role-index', 'role-create','role-edit','role-delete','role-show'];

        $this->user = ['user-index', 'user-create','user-edit','user-delete','user-show'];

        $this->quotation = ['quotation-index', 'quotation-create','quotation-edit','quotation-delete','quotation-show'];

        $this->invoice = ['invoice-index', 'invoice-create','invoice-edit','invoice-delete','invoice-show'];

        $this->receipt = ['receipt-index', 'receipt-create','receipt-edit','receipt-delete','receipt-show'];

        $this->po = ['po-index', 'po-create','po-edit','po-delete','po-show'];

        $this->do = ['do-index', 'do-create','do-edit','do-delete','do-show'];

        $this->customer = ['customer-index', 'customer-create','customer-edit','customer-delete','customer-show'];

        $this->dealer = ['dealer-index', 'dealer-create','dealer-edit','dealer-delete','dealer-show'];

        $this->currency = ['currency-index', 'currency-create','currency-edit','currency-delete','currency-show'];

        $this->bank = ['bank-index', 'bank-create','bank-edit','bank-delete','bank-show'];

        $this->authorizer = ['authorizer-index', 'authorizer-create','authorizer-edit','authorizer-delete','authorizer-show'];
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // create permission
        $this->permission_create($this->dashboard);
        $this->permission_create($this->permission);
        $this->permission_create($this->role);
        $this->permission_create($this->user);
        $this->permission_create($this->quotation);
        $this->permission_create($this->invoice);
        $this->permission_create($this->receipt);
        $this->permission_create($this->po);
        $this->permission_create($this->do);
        $this->permission_create($this->customer);
        $this->permission_create($this->dealer);
        $this->permission_create($this->currency);
        $this->permission_create($this->bank);
        $this->permission_create($this->authorizer);

        // create role and give permission to this role
        $this->role_create('system', [Permission::all()]);
        $this->role_create('admin', [Permission::all()]);
        $this->role_create('moderator', [Permission::all()]);
    }

    public function permission_create($permissions){
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }

    public function role_create($role_name, $permissions){
        $role =  Role::create(['name' => $role_name]);
        foreach ($permissions as $permission) {
            $role->givePermissionTo($permission);
        }
    }

}

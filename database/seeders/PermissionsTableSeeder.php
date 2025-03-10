<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                'id'    => 1,
                'title' => 'user_management_access',
            ],
            [
                'id'    => 2,
                'title' => 'permission_create',
            ],
            [
                'id'    => 3,
                'title' => 'permission_edit',
            ],
            [
                'id'    => 4,
                'title' => 'permission_show',
            ],
            [
                'id'    => 5,
                'title' => 'permission_delete',
            ],
            [
                'id'    => 6,
                'title' => 'permission_access',
            ],
            [
                'id'    => 7,
                'title' => 'role_create',
            ],
            [
                'id'    => 8,
                'title' => 'role_edit',
            ],
            [
                'id'    => 9,
                'title' => 'role_show',
            ],
            [
                'id'    => 10,
                'title' => 'role_delete',
            ],
            [
                'id'    => 11,
                'title' => 'role_access',
            ],
            [
                'id'    => 12,
                'title' => 'user_create',
            ],
            [
                'id'    => 13,
                'title' => 'user_edit',
            ],
            [
                'id'    => 14,
                'title' => 'user_show',
            ],
            [
                'id'    => 15,
                'title' => 'user_delete',
            ],
            [
                'id'    => 16,
                'title' => 'user_access',
            ],
            [
                'id'    => 17,
                'title' => 'audit_log_show',
            ],
            [
                'id'    => 18,
                'title' => 'audit_log_access',
            ],
            [
                'id'    => 19,
                'title' => 'task_management_access',
            ],
            [
                'id'    => 20,
                'title' => 'task_status_create',
            ],
            [
                'id'    => 21,
                'title' => 'task_status_edit',
            ],
            [
                'id'    => 22,
                'title' => 'task_status_show',
            ],
            [
                'id'    => 23,
                'title' => 'task_status_delete',
            ],
            [
                'id'    => 24,
                'title' => 'task_status_access',
            ],
            [
                'id'    => 25,
                'title' => 'task_tag_create',
            ],
            [
                'id'    => 26,
                'title' => 'task_tag_edit',
            ],
            [
                'id'    => 27,
                'title' => 'task_tag_show',
            ],
            [
                'id'    => 28,
                'title' => 'task_tag_delete',
            ],
            [
                'id'    => 29,
                'title' => 'task_tag_access',
            ],
            [
                'id'    => 30,
                'title' => 'task_create',
            ],
            [
                'id'    => 31,
                'title' => 'task_edit',
            ],
            [
                'id'    => 32,
                'title' => 'task_show',
            ],
            [
                'id'    => 33,
                'title' => 'task_delete',
            ],
            [
                'id'    => 34,
                'title' => 'task_access',
            ],
            [
                'id'    => 35,
                'title' => 'tasks_calendar_access',
            ],
            [
                'id'    => 36,
                'title' => 'basic_c_r_m_access',
            ],
            [
                'id'    => 37,
                'title' => 'crm_status_create',
            ],
            [
                'id'    => 38,
                'title' => 'crm_status_edit',
            ],
            [
                'id'    => 39,
                'title' => 'crm_status_show',
            ],
            [
                'id'    => 40,
                'title' => 'crm_status_delete',
            ],
            [
                'id'    => 41,
                'title' => 'crm_status_access',
            ],
            [
                'id'    => 42,
                'title' => 'crm_customer_create',
            ],
            [
                'id'    => 43,
                'title' => 'crm_customer_edit',
            ],
            [
                'id'    => 44,
                'title' => 'crm_customer_show',
            ],
            [
                'id'    => 45,
                'title' => 'crm_customer_delete',
            ],
            [
                'id'    => 46,
                'title' => 'crm_customer_access',
            ],
            [
                'id'    => 47,
                'title' => 'crm_note_create',
            ],
            [
                'id'    => 48,
                'title' => 'crm_note_edit',
            ],
            [
                'id'    => 49,
                'title' => 'crm_note_show',
            ],
            [
                'id'    => 50,
                'title' => 'crm_note_delete',
            ],
            [
                'id'    => 51,
                'title' => 'crm_note_access',
            ],
            [
                'id'    => 52,
                'title' => 'crm_document_create',
            ],
            [
                'id'    => 53,
                'title' => 'crm_document_edit',
            ],
            [
                'id'    => 54,
                'title' => 'crm_document_show',
            ],
            [
                'id'    => 55,
                'title' => 'crm_document_delete',
            ],
            [
                'id'    => 56,
                'title' => 'crm_document_access',
            ],
            [
                'id'    => 57,
                'title' => 'setting_access',
            ],
            [
                'id'    => 58,
                'title' => 'source_create',
            ],
            [
                'id'    => 59,
                'title' => 'source_edit',
            ],
            [
                'id'    => 60,
                'title' => 'source_delete',
            ],
            [
                'id'    => 61,
                'title' => 'source_access',
            ],
            [
                'id'    => 62,
                'title' => 'clinic_management_access',
            ],
            [
                'id'    => 63,
                'title' => 'clinic_create',
            ],
            [
                'id'    => 64,
                'title' => 'clinic_edit',
            ],
            [
                'id'    => 65,
                'title' => 'clinic_show',
            ],
            [
                'id'    => 66,
                'title' => 'clinic_delete',
            ],
            [
                'id'    => 67,
                'title' => 'clinic_access',
            ],
            [
                'id'    => 68,
                'title' => 'profile_password_edit',
            ],
        ];

        Permission::insert($permissions);
    }
}

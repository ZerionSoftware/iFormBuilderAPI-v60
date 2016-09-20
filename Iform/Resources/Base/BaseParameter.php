<?php namespace Iform\Resources\Base;

class BaseParameter {

    public static function page($localizations = null)
    {
        $params = array(
            "id",
            "name",
            "permissions",
            "global_id",
            "label",
            "description",
            "version",
            "created_date",
            "created_by",
            "modified_date",
            "modified_by",
            "is_disabled",
            "reference_id_1",
            "reference_id_2",
            "reference_id_3",
            "reference_id_4",
            "reference_id_5",
            "icon",
            "sort_order",
            "page_javascript",
            "label_icons",
        );

        if (! is_null($localizations)) $params[] = "localizations";

        return $params;
    }

    public static function element($localizations = null)
    {
        $params = array(
            'id',
            'name',
            'global_id',
            'version',
            'label',
            'description',
            'data_type',
            'data_size',
            'created_date',
            'created_by',
            'modified_date',
            'modified_by',
            'widget_type',
            'sort_order',
            'optionlist_id',
            'default_value',
            'low_value',
            'high_value',
            'dynamic_value',
            'condition_value',
            'client_validation',
            'is_disabled',
            'reference_id_1',
            'reference_id_2',
            'reference_id_3',
            'reference_id_4',
            'reference_id_5',
            'attachment_link',
            'is_readonly',
            'is_required',
            'validation_message',
            'is_action',
            'smart_tbl_search',
            'smart_tbl_search_col',
            'is_encrypt',
            'is_hide_typing',
            'on_change',
            'keyboard_type',
            'dynamic_label',
            'weighted_score'
        );

        if (! is_null($localizations)) $params[] = "localizations";

        return $params;
    }

    public static function user()
    {
        return array(
            "id",
            "username",
            "global_id",
            "first_name",
            "last_name",
            "email",
            "created_date",
            "is_locked",
            "roles"
        );
    }

    public static function profile()
    {
        return array(
            'id',
            'name',
            'global_id',
            'version',
            'address1',
            'address2',
            'city',
            'zip',
            'state',
            'country',
            'phone',
            'fax',
            'email',
            'max_user',
            'max_page',
            'is_active',
            'created_date',
            'type',
            'support_hours',
            'time_zone'
        );
    }


    public static function userGroup()
    {
        return array(
            'id',
            'users',
            'global_id',
            'version',
            'name',
            'created_date'
        );
    }

    public static function option($localizations = null)
    {
        $params = array(
            "id",
            "key_value",
            "global_id",
            "label",
            "sort_order",
            "condition_value",
            "score"
        );

        if (! is_null($localizations)) $params[] = "localizations";

        return $params;
    }

    public static function optionList()
    {
        return array(
            "id",
            "name",
            "global_id",
            "version",
            "created_date",
            "created_by",
            "modified_date",
            "modified_by",
            "reference_id",
            "option_icons"
        );
    }
}
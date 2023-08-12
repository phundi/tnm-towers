<?php
class Invite {
    public function __construct() {
        if (empty(route(4))) {
            return json(array(
                'message' => __('Function not found'),
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '1',
                    'error_text' => __('Function not found')
                )
            ), 400);
        }
        if (route(4) == 'add_invitation_link') {
            json($this->add_invitation_link());
        }
        elseif (route(4) == 'get_invitation_links') {
            json($this->get_invitation_links());
        }
        else{
            return json(array(
                'message' => __('Function not found'),
                'code' => 400,
                'errors'         => array(
                    'error_id'   => '10',
                    'error_text' => __('Function not found')
                )
            ), 400);
        }
    }

    public function add_invitation_link()
    {
        global $db, $_UPLOAD, $_DS,$config;
        if ($config->invite_links_system != 1) {
            return array(
                'status' => 400,
                'message' => __('Forbidden')
            );
        }
        if (IfCanGenerateLink(Auth()->id)) {
            $user_id = Auth()->id;
            $code  = uniqid(rand(), true);
            $db->insert('invitation_links',array('user_id' => $user_id,
                                                'code' => $code,
                                                'time' => time()));
            return array(
                'status' => 200,
                'message' => __('Code successfully generated'),
                'code' => $code
            );
        }
        else{
            return array(
                'status' => 400,
                'message' => 'You can not Generate link'
            );
        }
    }
    public function get_invitation_links()
    {
        global $db, $_UPLOAD, $_DS,$config;
        if ($config->invite_links_system != 1) {
            return array(
                'status' => 400,
                'message' => __('Forbidden')
            );
        }
        $trans = GetMyInvitaionCodes(Auth()->id);
        return array(
            'status' => 200,
            'data' => $trans
        );
    }






}
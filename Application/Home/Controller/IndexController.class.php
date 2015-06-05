<?php

namespace Home\Controller;

use Think\Controller;

class IndexController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->note = D('Note');
        $this->user = D('User');
    }

    public function index() {
        if (IS_POST) {
            $where = array(
                'active' => 1
            );
            $list = $this->note->where($where)->order('note_id DESC')->select();
            $return = array();
            foreach ($list as $value) {
                $value['create_time'] = date("Y-m-d H:i:s", $value['create_time']);
                $value['update_time'] = date("Y-m-d H:i:s", $value['update_time']);
                $return[] = $value;
            }
            outputListData($return);
        } else {
            $this->assign(array(
                'title_name' => 'AAnote',
                'content_name' => '流水单',
            ));
            $this->display();
        }
    }

    public function add() {
        if (IS_POST) {
            $data = I('post.');
            $user_info = $this->user->where(array('user_id' => $data['user_id']))->field('user_name')->find();
            $data['user_name'] = $user_info['user_name'];
            $scope_user_ids = explode(',', $data['scope_user_ids']);
            $scope_user_info = $this->user->where(array('user_id' => array('IN', $scope_user_ids)))->field('user_name')->select();
            $scope_user_names = array();
            foreach ($scope_user_info as $value) {
                $scope_user_names[] = $value['user_name'];
            }
            $data['scope_user_names'] = implode(',', $scope_user_names);
            $data['create_time'] = time();
            $data['update_time'] = time();
            $this->note->add($data);
        } else {
            $user_select = $this->user->field('user_id,user_name')->select();
            $this->assign(array(
                'user_select' => json_encode($user_select, JSON_UNESCAPED_UNICODE),
                'content_name' => '添加流水单',
            ));
            $this->display();
        }
    }

    public function del() {
        if (IS_POST) {
            $note_id = I('post.note_id');
            $data = array();
            $data['note_id'] = $note_id;
            $data['active'] = 0;
            $data['update_time'] = time();
            $this->note->where(array('note_id' => $note_id))->save($data);
        }
    }

    public function edit() {
        if (IS_POST) {
            $data = I('post.');
            $user_info = $this->user->where(array('user_id' => $data['user_id']))->field('user_name')->find();
            $data['user_name'] = $user_info['user_name'];
            $scope_user_ids = explode(',', $data['scope_user_ids']);
            $scope_user_info = $this->user->where(array('user_id' => array('IN', $scope_user_ids)))->field('user_name')->select();
            $scope_user_names = array();
            foreach ($scope_user_info as $value) {
                $scope_user_names[] = $value['user_name'];
            }
            $data['scope_user_names'] = implode(',', $scope_user_names);
            $data['update_time'] = time();
            $this->note->where(array('note_id' => $data['note_id']))->save($data);
        } else {
            $note_id = I('get.note_id');
            $note_info = $this->note->where(array('note_id' => $note_id))->find();
            $user_select = $this->user->field('user_id,user_name')->select();
            $this->assign(array(
                'user_select' => json_encode($user_select, JSON_UNESCAPED_UNICODE),
                'note_info' => $note_info,
                'content_name' => '编辑流水单',
            ));
            $this->display();
        }
    }

    public function calculate() {
        if (IS_POST) {
            
        } else {
            $where = array(
                'active' => 1,
                'status' => 0,
            );
            $min_info = $this->note->where($where)->order('note_id ASC')->find();
            $max_info = $this->note->where($where)->order('note_id DESC')->find();
            $this->assign(array(
                'min_id' => $min_info['note_id'],
                'max_id' => $max_info['note_id'],
                'min' => $this->getCalculateOutputData($min_info),
                'max' => $this->getCalculateOutputData($max_info),
                'content_name' => 'AA计算',
            ));
            $this->display();
        }
    }

    private function getCalculateOutputData($data) {
        return '详细: ' . $data['note_id'] . ' | ' . $data['money'] . ' | ' . $data['spending_time'] . ' | ' . $data['user_name'] . ' | ' . $data['scope_user_names'] . ' | ' . $data['remark'];
    }

}

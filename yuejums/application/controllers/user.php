<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 *
 */
class User extends CI_Controller
{
    /**
    获取所有用户信息，分页
    */
    public function  userall($cate_id=0,$offset=0){
        $user_name=$this->input->get('keyword');
        $this->load->model("user_model");
        // $contents=$this->user_model->getall();
        $total_row=$this->user_model->get_all_count($user_name);
        // var_dump($total_row);
        // die();
        $this->load->library('pagination');
        $config['base_url']='user/userall/';
        $config['total_rows']=$total_row;
        $config['per_page']=6;
        $config['uri_segment']=3;
        // $config['first_link']="首页";
        // $config['last_link']="尾页";
        $config['prev_link']="上一页";
        $config['next_link']="下一页";
        $config['num_tag_open']='<li>';
        $config['num_tag_close']='</li>';
        $config['first_tag_open']='<li>';
        $config['first_tag_close']='</li>';
        $config['last_tag_open']='<li>';
        $config['last_tag_close']='</li>';
        $config['cur_tag_open']='<li class="page_selected"><a href="user/userall/" class="page_selected">';
        $config['cur_tag_close']='</a></li>';
        $config['next_tag_open']='<li>';
        $config['next_tag_close']='</li>';
        $config['prev_tag_open']='<li>';
        $config['prev_tag_close']='</li>';
        $this->pagination->initialize($config);
        $offset=$this->uri->segment(3);
        $offset=!$offset?0:$offset;
       
        $contents=$this->user_model->getall();
        $categories=$this->user_model->get_by_page($user_name,$config['per_page'],$offset);
        //$user_categories=$this->user_model->get_user_category();
        
        $this -> load ->view('user_control',array(
            'contents'=>$contents,
            'categories'=>$categories
           // 'user_categories'=>$user_categories
        ));

    }
    /**
    删除功能
    */
    public function del(){
        $id=$this->uri->segment(3);
        $this->load->model('user_model');
        $result=$this->user_model->del_user($id);
        if($result){
            redirect('user/userall');
            //$this->userall();
        }
        else {
            echo "删除失败";
        }
    }
    /**
    更新的内容更新到数据库
    */
    public function do_update(){
        $newusername=$this->input->post('username');
        $password=$this->input->post('password');
        $email=$this->input->post('email');
        $tel=$this->input->post('tel');
        $hid=$this->input->post('hid');

        $this->load->model('comment_model');
        $result=$this->comment_model->update_id($hid,$newusername,$password,$email,$tel);
        

        if($result){
            redirect('user/userall');
        }
        else{
            echo '更新失败';
        }

    }

    /**
     * 修改 获取id，通过id获取用户信息，显示在界面
     */
    public function user_bounce(){
        $id=$this->uri->segment(3);
        $this->load->model('user_model');
        $result=$this->user_model->update_user($id);
        $arr['up']=$result;
        $this->load->view('user_bounce.php',$arr);
    }

    /**
     * 获取用户信息，显示在界面上
     */
    public function user_edit(){
        $id=$this->input->post('user_id');
        $this->load->model('user_model');
        $detail=$this->user_model->user_message($id);
        echo json_encode($detail);
    }

    /**
     *批量删除
     */
     public function delete_many(){
        $id=$this->input->post('delete_id');
        $this->load->model('user_model');
        $data=$this->user_model->batch_delete($id);
        if($data){
            echo "success";
        }else{
            echo "failed";
        }

    }
}
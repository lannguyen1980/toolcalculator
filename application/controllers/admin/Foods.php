<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Foods extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('admin/foods');
		$this->load->model('admin/Foods_model');

        /* Title Page :: Common */
        $this->page_title->push(lang('menu_foods'));
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_foods'), 'admin/foods');
    }


	public function index()
	{
        if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin())
        {
            redirect('auth/login', 'refresh');
        }
        else
        {
            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

			// $this->data['foods'] = $this->Foods_model->foods()->result();
			//innit cache drive (300=5 minutes)
			$iInterval=3600;	//60 phut
			$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
			//get foods from cache
			$lstadminfoods=$this->cache->get('adminfoods'); 
			$lstHtmladminfoods=$this->cache->get('adminfoodsHtml'); 
		
			if(!isset($lstadminfoods) || empty($lstadminfoods)){
				//Load danh sach thuc an from db
				$lstadminfoods= $this->Foods_model->foods()->result();
				// Save into the cache for 5 minutes
				$this->cache->save('adminfoods', $lstadminfoods, $iInterval);
			}
			// $this->data['cacheadminfoods'] ='yescache';
			if(!isset($lstHtmladminfoods) || empty($lstHtmladminfoods)){
				// $this->data['cacheadminfoods'] ='nocache';
				if(isset($lstadminfoods) && !empty($lstadminfoods)){
					foreach ($lstadminfoods as $values){
						$lstHtmladminfoods.='<tr>';
						$lstHtmladminfoods.='<td>' . htmlspecialchars($values->sTenThucAn, ENT_QUOTES, 'UTF-8') .'</td>';
						$lstHtmladminfoods.='<td>' . htmlspecialchars($values->sSLplusDVT, ENT_QUOTES, 'UTF-8') .'</td>';
						$lstHtmladminfoods.='<td class="float-right">' . round($values->fCalori * 100) / 100 .'</td>';
						$lstHtmladminfoods.='<td class="float-right">' . round($values->fDam * 100) / 100 .'</td>';
						$lstHtmladminfoods.='<td class="float-right">' . round($values->fBeo * 100) / 100 .'</td>';
						$lstHtmladminfoods.='<td class="float-right">' . round($values->fBotOrDuong * 100) / 100 .'</td>';
						$lstHtmladminfoods.='<td class="float-right">' . round($values->fXo * 100) / 100 .'</td>';
						$lstHtmladminfoods.='<td>' . anchor("admin/foods/edit/".$values->iIDThucAn, lang('actions_edit')) .'</td>';
						$lstHtmladminfoods.='<td data-id="' . $values->iIDThucAn.'"><a class="delete-me" href="#">Delete</a></td>';
						$lstHtmladminfoods.='</tr>';
					}
					$this->cache->save('adminfoodsHtml', $lstHtmladminfoods, $iInterval);
				}
			}

			$this->data['adminfoods'] =$lstadminfoods;
			$this->data['lstHtmladminfoods'] =$lstHtmladminfoods;

            /* Load Template */
            $this->template->admin_render('admin/foods/index', $this->data);
        }
    }


	public function create()
	{
		if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, 'Thêm mới', 'admin/foods/create');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

		/* Validate form input */
		$this->form_validation->set_rules('sTenThucAn', 'tên thức ăn', 'required');
		$this->form_validation->set_rules('sSLplusDVT', 'số lượng', 'required');
		$this->form_validation->set_rules('fCalori', 'calori', 'required');
		$this->form_validation->set_rules('fDam', 'chất đạm', 'required');
		$this->form_validation->set_rules('fBeo', 'chất béo', 'required');
		$this->form_validation->set_rules('fBotOrDuong', 'chất bột/đường', 'required');
		$this->form_validation->set_rules('fXo', 'chất xơ', 'required');

		if ($this->form_validation->run() == TRUE)
		{
			$new_food_id = $this->Foods_model->create_food($this->input->post('sTenThucAn'), 
														$this->input->post('sSLplusDVT'),
														$this->input->post('fCalori'),
														$this->input->post('fDam'),
														$this->input->post('fBeo'),
														$this->input->post('fBotOrDuong'),
														$this->input->post('fXo')
													);
			if ($new_food_id)
			{
				//delete cache
				$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
				$this->cache->delete('foods');
				$this->cache->delete('foodsHtml');
				//
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect('admin/foods', 'refresh');
			}
		}
		else
		{
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['sTenThucAn'] = array(
				'name'  => 'sTenThucAn',
				'id'    => 'sTenThucAn',
				'type'  => 'text',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('sTenThucAn')
			);
			$this->data['sSLplusDVT'] = array(
				'name'  => 'sSLplusDVT',
				'id'    => 'sSLplusDVT',
				'type'  => 'text',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('sSLplusDVT')
			);
			$this->data['fDam'] = array(
				'name'  => 'fDam',
				'id'    => 'fDam',
				'type'  => 'number',
				'step'  => '0.01',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('fDam')
			);
			$this->data['fBeo'] = array(
				'name'  => 'fBeo',
				'id'    => 'fBeo',
				'type'  => 'number',
				'step'  => '0.01',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('fBeo')
			);
			$this->data['fBotOrDuong'] = array(
				'name'  => 'fBotOrDuong',
				'id'    => 'fBotOrDuong',
				'type'  => 'number',
				'step'  => '0.01',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('fBotOrDuong')
			);
			$this->data['fXo'] = array(
				'name'  => 'fXo',
				'id'    => 'fXo',
				'type'  => 'number',
				'step'  => '0.01',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('fXo')
			);
			$this->data['fCalori'] = array(
				'name'  => 'fCalori',
				'id'    => 'fCalori',
				'type'  => 'number',
				'step'  => '0.01',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('fCalori'),
				'readonly' => 'readonly'
			);

            /* Load Template */
            $this->template->admin_render('admin/foods/create', $this->data);
		}
	}


	// public function delete()
	// {
    //     if ( ! $this->ion_auth->logged_in())
    //     {
    //         redirect('auth/login', 'refresh');
    //     }
    //     elseif ( ! $this->ion_auth->is_admin())
	// 	{
    //         return show_error('You must be an administrator to view this page.');
    //     }
    //     else
    //     {
    //         $this->load->view('admin/foods/delete');
    //     }
	// }

	/**
    * Delete Data from this method.
    *
    * @return Response
   */
	public function delete($id)
	{
		// $this->load->database();
		// $this->db->where('iIDThucAn', $id);
		// $this->db->delete('cal_thucan');
		$result=$this->Foods_model->delete_food($id);
		if(isset($result)){
			//delete cache
			$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
			$this->cache->delete('foods');
			$this->cache->delete('foodsHtml');
			//
			echo json_encode(['success'=>true]);
		}else{
			echo json_encode(['success'=>false]);
		}
		

		// if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin() OR ! $id OR empty($id))
		// {
		// 	redirect('auth', 'refresh');
		// }
		// $food_delete = $this->Foods_model->delete_food($id);
		// if ($food_delete)
		// {
		// 	//$this->session->set_flashdata('message', $this->lang->line('edit_food_saved'));

		// 	echo json_encode(['success'=>true]);
		// }
		// else
		// {
		// 	echo json_encode(['success'=>false]);
		// }

		// redirect('admin/foods', 'refresh');


		
	}


	public function edit($id)
	{
		if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin() OR ! $id OR empty($id))
		{
			redirect('auth', 'refresh');
		}

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, 'Sửa', 'admin/foods/edit');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
		$food = $this->Foods_model->food($id)->row();

		/* Validate form input */
		// $this->form_validation->set_rules('sTenThucAn', $this->lang->line('edit_food_validation_name_label'), 'required');
		$this->form_validation->set_rules('sTenThucAn', 'lang:create_food_validation_name_label', 'required');
		$this->form_validation->set_rules('sSLplusDVT', 'lang:create_food_validation_name_label', 'required');
		$this->form_validation->set_rules('fCalori', 'lang:create_food_validation_name_label', 'required');
		$this->form_validation->set_rules('fDam', 'lang:create_food_validation_name_label', 'required');
		$this->form_validation->set_rules('fBeo', 'lang:create_food_validation_name_label', 'required');
		$this->form_validation->set_rules('fBotOrDuong', 'lang:create_food_validation_name_label', 'required');
		$this->form_validation->set_rules('fXo', 'lang:create_food_validation_name_label', 'required');

		if (isset($_POST) && ! empty($_POST))
		{
			if ($this->form_validation->run() == TRUE)
			{
				$food_update = $this->Foods_model->update_food($id, $_POST['sTenThucAn'], 
															$_POST['sSLplusDVT'],
															$_POST['fCalori'],
															$_POST['fDam'],
															$_POST['fBeo'],
															$_POST['fBotOrDuong'],
															$_POST['fXo']
														);

				if ($food_update)
				{
					//delete cache
					$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
					$this->cache->delete('foods');
					$this->cache->delete('foodsHtml');
					//
					$this->session->set_flashdata('message', $this->lang->line('edit_food_saved'));

                    /* IN TEST */
                    // $this->db->update('cal_thucan', array('bgcolor' => $_POST['food_bgcolor']), 'id = '.$id);
				}
				else
				{
					$this->session->set_flashdata('message', $this->ion_auth->errors());
				}

				redirect('admin/foods', 'refresh');
			}
		}

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $this->data['food']   = $food;

		// $readonly = $this->config->item('admin_food', 'ion_auth') === $food->name ? 'readonly' : '';

		$this->data['sTenThucAn'] = array(
			'name'  => 'sTenThucAn',
			'id'    => 'sTenThucAn',
			'type'  => 'text',
			'class' => 'form-control',
			'value' => $this->form_validation->set_value('sTenThucAn', $food->sTenThucAn)
		);
		$this->data['sSLplusDVT'] = array(
			'name'  => 'sSLplusDVT',
			'id'    => 'sSLplusDVT',
			'type'  => 'text',
			'class' => 'form-control',
			'value' => $this->form_validation->set_value('sSLplusDVT', $food->sSLplusDVT)
		);
		$this->data['fDam'] = array(
			'name'  => 'fDam',
			'id'    => 'fDam',
			'type'  => 'number',
			'step'  => '0.01',
			'class' => 'form-control',
			'value' =>  $this->form_validation->set_value('fDam', round($food->fDam * 100 / 100))
		);
		$this->data['fBeo'] = array(
			'name'  => 'fBeo',
			'id'    => 'fBeo',
			'type'  => 'number',
			'step'  => '0.01',
			'class' => 'form-control',
			'value' =>  $this->form_validation->set_value('fDam', round($food->fBeo * 100) / 100)
		);
		$this->data['fBotOrDuong'] = array(
			'name'  => 'fBotOrDuong',
			'id'    => 'fBotOrDuong',
			'type'  => 'number',
			'step'  => '0.01',
			'class' => 'form-control',
			'value' =>  $this->form_validation->set_value('fDam', round($food->fBotOrDuong * 100) / 100)
		);
		$this->data['fXo'] = array(
			'name'  => 'fXo',
			'id'    => 'fXo',
			'type'  => 'number',
			'step'  => '0.01',
			'class' => 'form-control',
			'value' =>  $this->form_validation->set_value('fDam', round($food->fXo * 100) / 100)
		);
		$this->data['fCalori'] = array(
			'name'  => 'fCalori',
			'id'    => 'fCalori',
			'type'  => 'number',
			'step'  => '0.01',
			'class' => 'form-control',
			'value' =>  $this->form_validation->set_value('fDam', round($food->fCalori * 100) / 100),
			'readonly' => 'readonly'
		);

        /* Load Template */
        $this->template->admin_render('admin/foods/edit', $this->data);
	}
}

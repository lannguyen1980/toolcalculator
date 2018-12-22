<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Toolcals extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('admin/toolcals');
		$this->load->model('admin/Toolcals_model');
		$this->load->model('admin/Foods_model');

        /* Title Page :: Common */
        $this->page_title->push(lang('menu_toolcals'));
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_toolcals'), 'admin/toolcals');
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

            $this->data['toolcals'] = $this->Toolcals_model->toolcals()->result();

            /* Load Template */
            $this->template->admin_render('admin/toolcals/index', $this->data);
        }
    }


	public function create()
	{
		if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin())
		{
			redirect('auth', 'refresh');
		}

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, 'Thêm mới', 'admin/toolcals/create');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

		/* Validate form input */
		$this->form_validation->set_rules('dDateCheDoAn', 'ngày tạo', 'required');
		$this->form_validation->set_rules('sTenKhachHang', 'tên khách hàng', 'required');
		$this->form_validation->set_rules('fTuoi', 'tuổi', 'required');
		$this->form_validation->set_rules('fCao', 'chiều cao', 'required');
		$this->form_validation->set_rules('fNang', 'cân nặng', 'required');
		$this->form_validation->set_rules('fVongEo', 'vòng eo', 'required');
		$this->form_validation->set_rules('fVongCo', 'vòng cổ', 'required');
		if($this->input->post('iGioTinh')=='0'){
			$this->form_validation->set_rules('fVongMong', 'vòng mông', 'required');
		}

		if ($this->form_validation->run() == TRUE)
		{
			$additional_data = array(
				'dDateCheDoAn' =>  $this->input->post('dDateCheDoAn'),
				'sTenKhachHang'  => $this->input->post('sTenKhachHang'),
				'iGioTinh'    => $this->input->post('iGioTinh'),
				'fTuoi'      => $this->input->post('fTuoi'),
				'fCao'      => $this->input->post('fCao'),
				'fNang'      => $this->input->post('fNang'),
				'iIDCuongDo'      => $this->input->post('iIDCuongDo'),
				'fBMI'      => $this->input->post('fBMI'),
				'fBMR'      => $this->input->post('fBMR'),
				'fTDEE'      => $this->input->post('fTDEE'),
				'sNguyCoBeoPhi'      => $this->input->post('sNguyCoBeoPhi'),
				'fVongEo'      => $this->input->post('fVongEo'),
				'fVongCo'      => $this->input->post('fVongCo'),
				'fVongMong'      => $this->input->post('fVongMong'),
				'fTiLeMo'      => $this->input->post('fTiLeMo'),
				'iIDMacro'      => $this->input->post('iIDMacro'),
				'fProteinPercent'      => $this->input->post('fProteinPercent'),
				'fProteinQty'      => $this->input->post('fProteinQty'),
				'fCarbohydratePercent'      => $this->input->post('fCarbohydratePercent'),
				'fFatQty'      => $this->input->post('fFatQty'),
				'iSoBuaAnNgay'      => $this->input->post('iSoBuaAnNgay'),
				'fProteinNgay'      => $this->input->post('fProteinNgay'),
				'fCarbohydrateNgay'      => $this->input->post('fCarbohydrateNgay'),
				'fFatNgay'      => $this->input->post('fFatNgay'),
				'sLoiKhuyen'      => $this->input->post('sLoiKhuyen'),
				'fTangOrGiamKG'      => $this->input->post('fTangOrGiamKG'),
				'iIDTocDoTangOrGiam'      => $this->input->post('iIDTocDoTangOrGiam'),
				'fLuongCalories'      => $this->input->post('fLuongCalories'),
				'iSoNgayDuKien'      => $this->input->post('iSoNgayDuKien'),
				'dCreateDate'      => date('Y-m-d H:i:s')
			);
			$additional_data_details = array(
				'hideBua1'      => $this->input->post('hideBua1'),
				'hideBua2'      => $this->input->post('hideBua2'),
				'hideBua3'      => $this->input->post('hideBua3'),
				'hideBua4'      => $this->input->post('hideBua4'),
				'hideBua5'      => $this->input->post('hideBua5'),
				'hideBua6'      => $this->input->post('hideBua6')
			);
			$new_toolcal_id = $this->Toolcals_model->create_toolcal($additional_data,$additional_data_details);

			if ($new_toolcal_id)
			{
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect('admin/toolcals', 'refresh');
			}
		}
		else
		{
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

			$this->data['dDateCheDoAn'] = array(
				'name'  => 'dDateCheDoAn',
				'id'    => 'dDateCheDoAn',
				'type'  => 'date',
                'class' => 'form-control',
				'value' => date('Y-m-d') //$this->form_validation->set_value('dDateCheDoAn')
			);
			$this->data['sTenKhachHang'] = array(
				'name'  => 'sTenKhachHang',
				'id'    => 'sTenKhachHang',
				'type'  => 'text',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('sTenKhachHang')
			);
			$this->data['iGioTinh'] = array(
				0  => 'Nữ',
				1    => 'Nam'
			);
			$this->data['fTuoi'] = array(
				'name'  => 'fTuoi',
				'id'    => 'fTuoi',
				'type'  => 'number',
				'step'  => '1',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('fTuoi')
			);
			$this->data['fCao'] = array(
				'name'  => 'fCao',
				'id'    => 'fCao',
				'type'  => 'number',
				'step'  => '0.01',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('fCao')
			);
			$this->data['fNang'] = array(
				'name'  => 'fNang',
				'id'    => 'fNang',
				'type'  => 'number',
				'step'  => '0.01',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('fNang')
			);

			$this->data['lstCuongDo'] = $this -> Toolcals_model -> getAllCuongDo();
			// $this->data['iIDCuongDo'] = array(
			// 	'name'  => 'iIDCuongDo',
			// 	'id'    => 'iIDCuongDo',
			// 	'type'  => 'number',
			// 	'step'  => '1',
            //     'class' => 'form-control',
			// 	'value' => $this->form_validation->set_value('iIDCuongDo')
			// );
			$this->data['fBMI'] = array(
				'name'  => 'fBMI',
				'id'    => 'fBMI',
				'type'  => 'number',
				'step'  => '0.01',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('fBMI'),
				'readonly' => 'readonly'
			);
			$this->data['fBMR'] = array(
				'name'  => 'fBMR',
				'id'    => 'fBMR',
				'type'  => 'number',
				'step'  => '0.01',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('fBMR'),
				'readonly' => 'readonly'
			);
			$this->data['fTDEE'] = array(
				'name'  => 'fTDEE',
				'id'    => 'fTDEE',
				'type'  => 'number',
				'step'  => '0.01',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('fTDEE'),
				'readonly' => 'readonly'
			);
			$this->data['sNguyCoBeoPhi'] = array(
				'name'  => 'sNguyCoBeoPhi',
				'id'    => 'sNguyCoBeoPhi',
				'type'  => 'text',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('sNguyCoBeoPhi'),
				'readonly' => 'readonly'
			);
			$this->data['fVongEo'] = array(
				'name'  => 'fVongEo',
				'id'    => 'fVongEo',
				'type'  => 'number',
				'step'  => '0.01',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('fVongEo')
			);
			$this->data['fVongCo'] = array(
				'name'  => 'fVongCo',
				'id'    => 'fVongCo',
				'type'  => 'number',
				'step'  => '0.01',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('fVongCo')
			);
			$this->data['fVongMong'] = array(
				'name'  => 'fVongMong',
				'id'    => 'fVongMong',
				'type'  => 'number',
				'step'  => '0.01',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('fVongMong')
			);
			$this->data['fTiLeMo'] = array(
				'name'  => 'fTiLeMo',
				'id'    => 'fTiLeMo',
				'type'  => 'number',
				'step'  => '0.01',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('fTiLeMo'),
				'readonly' => 'readonly'
			);
			//Macro, protein,...
			$this->data['iIDMacro'] = array(
				1  => 'Ăn kiêng vừa phải 1',
				2    => 'Ăn kiêng vừa phải 2',
				3    => 'Zone Diet',
				4    => 'Ăn ít chất béo (Low Fat)',
				5    => 'Ăn ít tinh bột (Low Carb)',
				6    => 'Rất ít tinh bột (Ketogenic)'
			);
			$this->data['fProteinPercent'] = array(
				'name'  => 'fProteinPercent',
				'id'    => 'fProteinPercent',
				'type'  => 'number',
				'step'  => '0.01',
                'class' => 'form-control',
				// 'placeholder' => '%',
				'value' => $this->form_validation->set_value('fProteinPercent'),
				'readonly' => 'readonly'
			);
			$this->data['fProteinQty'] = array(
				'name'  => 'fProteinQty',
				'id'    => 'fProteinQty',
				'type'  => 'number',
				'step'  => '0.01',
                'class' => 'form-control',
				// 'placeholder' => 'Grams',
				'value' => $this->form_validation->set_value('fProteinQty'),
				'readonly' => 'readonly'
			);
			$this->data['fCarbohydratePercent'] = array(
				'name'  => 'fCarbohydratePercent',
				'id'    => 'fCarbohydratePercent',
				'type'  => 'number',
				'step'  => '0.01',
                'class' => 'form-control',
				// 'placeholder' => '%',
				'value' => $this->form_validation->set_value('fCarbohydratePercent'),
				'readonly' => 'readonly'
			);
			$this->data['fCarbohydrateQty'] = array(
				'name'  => 'fCarbohydrateQty',
				'id'    => 'fCarbohydrateQty',
				'type'  => 'number',
				'step'  => '0.01',
                'class' => 'form-control',
				// 'placeholder' => 'Grams',
				'value' => $this->form_validation->set_value('fCarbohydrate'),
				'readonly' => 'readonly'
			);
			$this->data['fFatPercent'] = array(
				'name'  => 'fFatPercent',
				'id'    => 'fFatPercent',
				'type'  => 'number',
				'step'  => '0.01',
                'class' => 'form-control',
				// 'placeholder' => '%',
				'value' => $this->form_validation->set_value('fFatPercent'),
				'readonly' => 'readonly'
			);
			$this->data['fFatQty'] = array(
				'name'  => 'fFatQty',
				'id'    => 'fFatQty',
				'type'  => 'number',
				'step'  => '0.01',
                'class' => 'form-control',
				// 'placeholder' => 'Grams',
				'value' => $this->form_validation->set_value('fFatQty'),
				'readonly' => 'readonly'
			);
			$this->data['iMaxSoBuaAnNgay']=6;
			$this->data['iSoBuaAnNgay'] = array(
				1  => '1',
				2    => '2',
				3    => '3',
				4    => '4',
				5    => '5',
				6    => '6'
			);
			$this->data['fProteinNgay'] = array(
				'name'  => 'fProteinNgay',
				'id'    => 'fProteinNgay',
				'type'  => 'number',
				'step'  => '0.01',
                'class' => 'form-control',
				// 'placeholder' => 'Grams',
				'value' => $this->form_validation->set_value('fProteinNgay'),
				'readonly' => 'readonly'
			);
			$this->data['fCarbohydrateNgay'] = array(
				'name'  => 'fCarbohydrateNgay',
				'id'    => 'fCarbohydrateNgay',
				'type'  => 'number',
				'step'  => '0.01',
                'class' => 'form-control',
				// 'placeholder' => 'Grams',
				'value' => $this->form_validation->set_value('fCarbohydrateNgay'),
				'readonly' => 'readonly'
			);
			$this->data['fFatNgay'] = array(
				'name'  => 'fFatNgay',
				'id'    => 'fFatNgay',
				'type'  => 'number',
				'step'  => '0.01',
                'class' => 'form-control',
				// 'placeholder' => 'Grams',
				'value' => $this->form_validation->set_value('fFatNgay'),
				'readonly' => 'readonly'
			);

			////
			$this->data['sLoiKhuyen'] = array(
				'name'  => 'sLoiKhuyen',
				'id'    => 'sLoiKhuyen',
				'type'  => 'text',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('sLoiKhuyen'),
				'readonly' => 'readonly'
			);
			$this->data['fTangOrGiamKG'] = array(
				'name'  => 'fTangOrGiamKG',
				'id'    => 'fTangOrGiamKG',
				'type'  => 'number',
				'step'  => '0.01',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('fTangOrGiamKG'),
				'readonly' => 'readonly'
			);
			$this->data['iIDTocDoTangOrGiam'] = array(
				1  => 'Bình thường',
				2    => 'Chậm',
				3  => 'Nhanh',
				4  => 'Cấp tốc'
			);
			$this->data['fLuongCalories'] = array(
				'name'  => 'fLuongCalories',
				'id'    => 'fLuongCalories',
				'type'  => 'number',
				'step'  => '0.01',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('fLuongCalories'),
				'readonly' => 'readonly'
			);
			$this->data['iSoNgayDuKien'] = array(
				'name'  => 'iSoNgayDuKien',
				'id'    => 'iSoNgayDuKien',
				'type'  => 'number',
				'step'  => '1',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('iSoNgayDuKien'),
				'readonly' => 'readonly'
			);
			$this->data['sNote'] = array(
				'name'  => 'sNote',
				'id'    => 'sNote',
				'type'  => 'text',
                'class' => 'form-control',
				'value' => $this->form_validation->set_value('sNote')
			);

			//'hidden' fields
			// $this->data['hideBua1'] = array(
			// 	'name'  => 'hideBua1',
			// 	'id'    => 'hideBua1',
			// 	'type'  => 'hidden',
            //     'class' => 'form-control',
			// 	'value' => ''
			// );
			// $this->data['hideBua2'] = array(
			// 	'name'  => 'hideBua2',
			// 	'id'    => 'hideBua2',
			// 	'type'  => 'hidden',
            //     'class' => 'form-control',
			// 	'value' => ''
			// );
			// $this->data['hideBua3'] = array(
			// 	'name'  => 'hideBua3',
			// 	'id'    => 'hideBua3',
			// 	'type'  => 'hidden',
            //     'class' => 'form-control',
			// 	'value' => ''
			// );
			// $this->data['hideBua4'] = array(
			// 	'name'  => 'hideBua4',
			// 	'id'    => 'hideBua4',
			// 	'type'  => 'hidden',
            //     'class' => 'form-control',
			// 	'value' => ''
			// );
			// $this->data['hideBua5'] = array(
			// 	'name'  => 'hideBua5',
			// 	'id'    => 'hideBua5',
			// 	'type'  => 'hidden',
            //     'class' => 'form-control',
			// 	'value' => ''
			// );
			// $this->data['hideBua6'] = array(
			// 	'name'  => 'hideBua6',
			// 	'id'    => 'hideBua6',
			// 	'type'  => 'hidden',
            //     'class' => 'form-control',
			// 	'value' => ''
			// );

			//Load danh sach thuc an
			// $this->data['lstFoods'] = $this->Foods_model->foods()->result();
			//innit cache drive (300=5 minutes)
			$iInterval=3600;	//60 phut
			$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
			//get foods from cache
			$lstFoods=$this->cache->get('foods'); 
			$lstHtmlFoods=$this->cache->get('foodsHtml'); 
		
			if(!isset($lstFoods) || empty($lstFoods)){
				//Load danh sach thuc an from db
				$lstFoods= $this->Foods_model->foods()->result();
				// Save into the cache for 5 minutes
				$this->cache->save('foods', $lstFoods, $iInterval);
			}
			// $this->data['cachefoods'] ='yescache';
			if(!isset($lstHtmlFoods) || empty($lstHtmlFoods)){
				// $this->data['cachefoods'] ='nocache';
				if(isset($lstFoods) && !empty($lstFoods)){
					foreach ($lstFoods as $values){
						$lstHtmlFoods.='<tr id="' . $values->iIDThucAn . '">';
						$lstHtmlFoods.='<td class="vcenter"><input type="checkbox" name="ThucAn[]" id="chk_' . $values->iIDThucAn . '" value="1" /></td>';
						$lstHtmlFoods.='<td style="display: none">' . $values->iIDThucAn . '</td>';
						$lstHtmlFoods.='<td>' . htmlspecialchars($values->sTenThucAn, ENT_QUOTES, 'UTF-8') . '</td>';
						$lstHtmlFoods.='<td>' . htmlspecialchars($values->sSLplusDVT, ENT_QUOTES, 'UTF-8') . '</td>';
						$lstHtmlFoods.='<td class="col-number">' . round($values->fCalori * 100) / 100 . '</td>';
						$lstHtmlFoods.='<td class="col-number">' . round($values->fDam * 100) / 100 . '</td>';
						$lstHtmlFoods.='<td class="col-number">' . round($values->fBeo * 100) / 100 . '</td>';
						$lstHtmlFoods.='<td class="col-number">' . round($values->fBotOrDuong * 100) / 100 . '</td>';
						$lstHtmlFoods.='<td class="col-number">' . round($values->fXo * 100) / 100 . '</td>';
						$lstHtmlFoods.='</tr>';
					}
					$this->cache->save('foodsHtml', $lstHtmlFoods, $iInterval);
				}
			}

			$this->data['lstFoods'] =$lstFoods;
			$this->data['lstHtmlFoods'] =$lstHtmlFoods;
			

            /* Load Template */
            $this->template->admin_render('admin/toolcals/create', $this->data);
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
    //         $this->load->view('admin/toolcals/delete');
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
		$this->Toolcals_model->delete_toolcal($id);
		echo json_encode(['success'=>true]);

		// if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin() OR ! $id OR empty($id))
		// {
		// 	redirect('auth', 'refresh');
		// }
		// $toolcal_delete = $this->Toolcals_model->delete_toolcal($id);
		// if ($toolcal_delete)
		// {
		// 	//$this->session->set_flashdata('message', $this->lang->line('edit_toolcal_saved'));

		// 	echo json_encode(['success'=>true]);
		// }
		// else
		// {
		// 	echo json_encode(['success'=>false]);
		// }

		// redirect('admin/toolcals', 'refresh');


		
	}


	public function edit($id)
	{
		if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin() OR ! $id OR empty($id))
		{
			redirect('auth', 'refresh');
		}

        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, 'Sửa', 'admin/toolcals/edit');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Variables */
		$toolcal = $this->Toolcals_model->toolcal($id)->row();
		$toolcaldetails = $this->Toolcals_model->toolcaldetail($id)->result();

		/* Validate form input */
		$this->form_validation->set_rules('dDateCheDoAn', 'ngày tạo', 'required');
		$this->form_validation->set_rules('sTenKhachHang', 'tên khách hàng', 'required');
		$this->form_validation->set_rules('fTuoi', 'tuổi', 'required');
		$this->form_validation->set_rules('fCao', 'chiều cao', 'required');
		$this->form_validation->set_rules('fNang', 'cân nặng', 'required');
		$this->form_validation->set_rules('fVongEo', 'vòng eo', 'required');
		$this->form_validation->set_rules('fVongCo', 'vòng cổ', 'required');
		if($this->input->post('iGioTinh')=='0'){
			$this->form_validation->set_rules('fVongMong', 'vòng mông', 'required');
		}

		if (isset($_POST) && ! empty($_POST))
		{
			if ($this->form_validation->run() == TRUE)
			{
				$additional_data = array(
					'dDateCheDoAn' =>  $this->input->post('dDateCheDoAn'),
					'sTenKhachHang'  => $this->input->post('sTenKhachHang'),
					'iGioTinh'    => $this->input->post('iGioTinh'),
					'fTuoi'      => $this->input->post('fTuoi'),
					'fCao'      => $this->input->post('fCao'),
					'fNang'      => $this->input->post('fNang'),
					'iIDCuongDo'      => $this->input->post('iIDCuongDo'),
					'fBMI'      => $this->input->post('fBMI'),
					'fBMR'      => $this->input->post('fBMR'),
					'fTDEE'      => $this->input->post('fTDEE'),
					'sNguyCoBeoPhi'      => $this->input->post('sNguyCoBeoPhi'),
					'fVongEo'      => $this->input->post('fVongEo'),
					'fVongCo'      => $this->input->post('fVongCo'),
					'fVongMong'      => $this->input->post('fVongMong'),
					'fTiLeMo'      => $this->input->post('fTiLeMo'),
					'iIDMacro'      => $this->input->post('iIDMacro'),
					'fProteinPercent'      => $this->input->post('fProteinPercent'),
					'fProteinQty'      => $this->input->post('fProteinQty'),
					'fCarbohydratePercent'      => $this->input->post('fCarbohydratePercent'),
					'fFatQty'      => $this->input->post('fFatQty'),
					'iSoBuaAnNgay'      => $this->input->post('iSoBuaAnNgay'),
					'fProteinNgay'      => $this->input->post('fProteinNgay'),
					'fCarbohydrateNgay'      => $this->input->post('fCarbohydrateNgay'),
					'fFatNgay'      => $this->input->post('fFatNgay'),
					'sLoiKhuyen'      => $this->input->post('sLoiKhuyen'),
					'fTangOrGiamKG'      => $this->input->post('fTangOrGiamKG'),
					'iIDTocDoTangOrGiam'      => $this->input->post('iIDTocDoTangOrGiam'),
					'fLuongCalories'      => $this->input->post('fLuongCalories'),
					'iSoNgayDuKien'      => $this->input->post('iSoNgayDuKien'),
					'dCreateDate'      => date('Y-m-d H:i:s')
				);
				$additional_data_details = array(
					'hideBua1'      => $this->input->post('hideBua1'),
					'hideBua2'      => $this->input->post('hideBua2'),
					'hideBua3'      => $this->input->post('hideBua3'),
					'hideBua4'      => $this->input->post('hideBua4'),
					'hideBua5'      => $this->input->post('hideBua5'),
					'hideBua6'      => $this->input->post('hideBua6')
				);

				$toolcal_update = $this->Toolcals_model->update_toolcal($id, $additional_data, $additional_data_details);

				if ($toolcal_update)
				{
					$this->session->set_flashdata('message', $this->lang->line('edit_toolcal_saved'));

                    /* IN TEST */
                    // $this->db->update('cal_thucan', array('bgcolor' => $_POST['toolcal_bgcolor']), 'id = '.$id);
				}
				else
				{
					$this->session->set_flashdata('message', $this->ion_auth->errors());
				}

				redirect('admin/toolcals', 'refresh');
			}
		}

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));
        $this->data['toolcal']   = $toolcal;
		$this->data['toolcaldetails']   = $toolcaldetails;

		// $readonly = $this->config->item('admin_toolcal', 'ion_auth') === $toolcal->name ? 'readonly' : '';
		$dDateCheDoAn = new DateTime($toolcal->dDateCheDoAn);
		// $this->dDateCheDoAn =$dDateCheDoAn->format('Y-m-d');
		$this->data['dDateCheDoAn'] = array(
			'name'  => 'dDateCheDoAn',
			'id'    => 'dDateCheDoAn',
			'type'  => 'date',
			'class' => 'form-control',
			'value' => $this->form_validation->set_value('dDateCheDoAn', $dDateCheDoAn->format('Y-m-d'))
		);
		$this->data['sTenKhachHang'] = array(
			'name'  => 'sTenKhachHang',
			'id'    => 'sTenKhachHang',
			'type'  => 'text',
			'class' => 'form-control',
			'value' => $this->form_validation->set_value('sTenKhachHang', $toolcal->sTenKhachHang)
		);
		$this->data['iGioTinh'] = array(
			0  => 'Nữ',
			1    => 'Nam'
		);
		$this->data['fTuoi'] = array(
			'name'  => 'fTuoi',
			'id'    => 'fTuoi',
			'type'  => 'number',
			'step'  => '1',
			'class' => 'form-control',
			'value' => $this->form_validation->set_value('fTuoi', round($toolcal->fTuoi * 100 / 100))
		);
		$this->data['fCao'] = array(
			'name'  => 'fCao',
			'id'    => 'fCao',
			'type'  => 'number',
			'step'  => '0.01',
			'class' => 'form-control',
			'value' => $this->form_validation->set_value('fTuoi', round($toolcal->fCao * 100 / 100))
		);
		$this->data['fNang'] = array(
			'name'  => 'fNang',
			'id'    => 'fNang',
			'type'  => 'number',
			'step'  => '0.01',
			'class' => 'form-control',
			'value' => $this->form_validation->set_value('fTuoi', round($toolcal->fNang * 100 / 100))
		);

		$this->data['lstCuongDo'] = $this -> Toolcals_model -> getAllCuongDo();
		// $this->data['iIDCuongDo'] = array(
		// 	'name'  => 'iIDCuongDo',
		// 	'id'    => 'iIDCuongDo',
		// 	'type'  => 'number',
		// 	'step'  => '1',
		//     'class' => 'form-control',
		// 	'value' => $this->form_validation->set_value('iIDCuongDo')
		// );
		$this->data['fBMI'] = array(
			'name'  => 'fBMI',
			'id'    => 'fBMI',
			'type'  => 'number',
			'step'  => '0.01',
			'class' => 'form-control',
			'value' => $this->form_validation->set_value('fBMI', round($toolcal->fBMI * 100 / 100)),
			'readonly' => 'readonly'
		);
		$this->data['fBMR'] = array(
			'name'  => 'fBMR',
			'id'    => 'fBMR',
			'type'  => 'number',
			'step'  => '0.01',
			'class' => 'form-control',
			'value' => $this->form_validation->set_value('fBMR', round($toolcal->fBMR * 100 / 100)),
			'readonly' => 'readonly'
		);
		$this->data['fTDEE'] = array(
			'name'  => 'fTDEE',
			'id'    => 'fTDEE',
			'type'  => 'number',
			'step'  => '0.01',
			'class' => 'form-control',
			'value' => $this->form_validation->set_value('fTDEE', round($toolcal->fTDEE * 100 / 100)),
			'readonly' => 'readonly'
		);
		$this->data['sNguyCoBeoPhi'] = array(
			'name'  => 'sNguyCoBeoPhi',
			'id'    => 'sNguyCoBeoPhi',
			'type'  => 'text',
			'class' => 'form-control',
			'value' => $this->form_validation->set_value('sNguyCoBeoPhi', $toolcal->sNguyCoBeoPhi),
			'readonly' => 'readonly'
		);
		$this->data['fVongEo'] = array(
			'name'  => 'fVongEo',
			'id'    => 'fVongEo',
			'type'  => 'number',
			'step'  => '0.01',
			'class' => 'form-control',
			'value' => $this->form_validation->set_value('fVongEo', round($toolcal->fVongEo * 100 / 100))
		);
		$this->data['fVongCo'] = array(
			'name'  => 'fVongCo',
			'id'    => 'fVongCo',
			'type'  => 'number',
			'step'  => '0.01',
			'class' => 'form-control',
			'value' => $this->form_validation->set_value('fVongCo', round($toolcal->fVongCo * 100 / 100))
		);
		$this->data['fVongMong'] = array(
			'name'  => 'fVongMong',
			'id'    => 'fVongMong',
			'type'  => 'number',
			'step'  => '0.01',
			'class' => 'form-control',
			'value' => $this->form_validation->set_value('fVongMong', round($toolcal->fVongMong * 100 / 100))
		);
		$this->data['fTiLeMo'] = array(
			'name'  => 'fTiLeMo',
			'id'    => 'fTiLeMo',
			'type'  => 'number',
			'step'  => '0.01',
			'class' => 'form-control',
			'value' => $this->form_validation->set_value('fTiLeMo', round($toolcal->fTiLeMo * 100 / 100)),
			'readonly' => 'readonly'
		);
		//Macro, protein,...
		$this->data['iIDMacro'] = array(
			1  => 'Ăn kiêng vừa phải 1',
			2    => 'Ăn kiêng vừa phải 2',
			3    => 'Zone Diet',
			4    => 'Ăn ít chất béo (Low Fat)',
			5    => 'Ăn ít tinh bột (Low Carb)',
			6    => 'Rất ít tinh bột (Ketogenic)'
		);
		$this->data['fProteinPercent'] = array(
			'name'  => 'fProteinPercent',
			'id'    => 'fProteinPercent',
			'type'  => 'number',
			'step'  => '0.01',
			'class' => 'form-control',
			// 'placeholder' => '%',
			'value' => $this->form_validation->set_value('fProteinPercent', round($toolcal->fProteinPercent * 100 / 100)),
			'readonly' => 'readonly'
		);
		$this->data['fProteinQty'] = array(
			'name'  => 'fProteinQty',
			'id'    => 'fProteinQty',
			'type'  => 'number',
			'step'  => '0.01',
			'class' => 'form-control',
			// 'placeholder' => 'Grams',
			'value' => $this->form_validation->set_value('fProteinQty', round($toolcal->fProteinQty * 100 / 100)),
			'readonly' => 'readonly'
		);
		$this->data['fCarbohydratePercent'] = array(
			'name'  => 'fCarbohydratePercent',
			'id'    => 'fCarbohydratePercent',
			'type'  => 'number',
			'step'  => '0.01',
			'class' => 'form-control',
			// 'placeholder' => '%',
			'value' => $this->form_validation->set_value('fCarbohydratePercent', round($toolcal->fCarbohydratePercent * 100 / 100)),
			'readonly' => 'readonly'
		);
		$this->data['fCarbohydrateQty'] = array(
			'name'  => 'fCarbohydrateQty',
			'id'    => 'fCarbohydrateQty',
			'type'  => 'number',
			'step'  => '0.01',
			'class' => 'form-control',
			// 'placeholder' => 'Grams',
			'value' => $this->form_validation->set_value('fCarbohydrateQty', round($toolcal->fCarbohydrateQty * 100 / 100)),
			'readonly' => 'readonly'
		);
		$this->data['fFatPercent'] = array(
			'name'  => 'fFatPercent',
			'id'    => 'fFatPercent',
			'type'  => 'number',
			'step'  => '0.01',
			'class' => 'form-control',
			// 'placeholder' => '%',
			'value' => $this->form_validation->set_value('fFatPercent', round($toolcal->fFatPercent * 100 / 100)),
			'readonly' => 'readonly'
		);
		$this->data['fFatQty'] = array(
			'name'  => 'fFatQty',
			'id'    => 'fFatQty',
			'type'  => 'number',
			'step'  => '0.01',
			'class' => 'form-control',
			// 'placeholder' => 'Grams',
			'value' => $this->form_validation->set_value('fFatQty', round($toolcal->fFatQty * 100 / 100)),
			'readonly' => 'readonly'
		);
		$this->data['iMaxSoBuaAnNgay']=6;
		$this->data['iSoBuaAnNgay'] = array(
			1  => '1',
			2    => '2',
			3    => '3',
			4    => '4',
			5    => '5',
			6    => '6'
		);
		$this->data['fProteinNgay'] = array(
			'name'  => 'fProteinNgay',
			'id'    => 'fProteinNgay',
			'type'  => 'number',
			'step'  => '0.01',
			'class' => 'form-control',
			// 'placeholder' => 'Grams',
			'value' =>$this->form_validation->set_value('fProteinNgay', round($toolcal->fProteinNgay * 100 / 100)),
			'readonly' => 'readonly'
		);
		$this->data['fCarbohydrateNgay'] = array(
			'name'  => 'fCarbohydrateNgay',
			'id'    => 'fCarbohydrateNgay',
			'type'  => 'number',
			'step'  => '0.01',
			'class' => 'form-control',
			// 'placeholder' => 'Grams',
			'value' => $this->form_validation->set_value('fCarbohydrateNgay', round($toolcal->fCarbohydrateNgay * 100 / 100)),
			'readonly' => 'readonly'
		);
		$this->data['fFatNgay'] = array(
			'name'  => 'fFatNgay',
			'id'    => 'fFatNgay',
			'type'  => 'number',
			'step'  => '0.01',
			'class' => 'form-control',
			// 'placeholder' => 'Grams',
			'value' => $this->form_validation->set_value('fFatNgay', round($toolcal->fFatNgay * 100 / 100)),
			'readonly' => 'readonly'
		);

		////
		$this->data['sLoiKhuyen'] = array(
			'name'  => 'sLoiKhuyen',
			'id'    => 'sLoiKhuyen',
			'type'  => 'text',
			'class' => 'form-control',
			'value' => $this->form_validation->set_value('sLoiKhuyen',$toolcal->sLoiKhuyen),
			'readonly' => 'readonly'
		);
		$this->data['fTangOrGiamKG'] = array(
			'name'  => 'fTangOrGiamKG',
			'id'    => 'fTangOrGiamKG',
			'type'  => 'number',
			'step'  => '0.01',
			'class' => 'form-control',
			'value' => $this->form_validation->set_value('fTangOrGiamKG', round($toolcal->fTangOrGiamKG * 100 / 100)),
			'readonly' => 'readonly'
		);
		$this->data['iIDTocDoTangOrGiam'] = array(
			1  => 'Bình thường',
			2    => 'Chậm',
			3  => 'Nhanh',
			4  => 'Cấp tốc'
		);
		$this->data['fLuongCalories'] = array(
			'name'  => 'fLuongCalories',
			'id'    => 'fLuongCalories',
			'type'  => 'number',
			'step'  => '0.01',
			'class' => 'form-control',
			'value' => $this->form_validation->set_value('fLuongCalories', round($toolcal->fLuongCalories * 100 / 100)),
			'readonly' => 'readonly'
		);
		$this->data['iSoNgayDuKien'] = array(
			'name'  => 'iSoNgayDuKien',
			'id'    => 'iSoNgayDuKien',
			'type'  => 'number',
			'step'  => '1',
			'class' => 'form-control',
			'value' => $this->form_validation->set_value('iSoNgayDuKien', round($toolcal->iSoNgayDuKien * 100 / 100)),
			'readonly' => 'readonly'
		);
		$this->data['sNote'] = array(
			'name'  => 'sNote',
			'id'    => 'sNote',
			'type'  => 'text',
			'class' => 'form-control',
			'value' => $this->form_validation->set_value('sNote',$toolcal->sNote)
		);

	//'hidden' fields
	// $this->data['hideBua1'] = array(
		// 	'name'  => 'hideBua1',
		// 	'id'    => 'hideBua1',
		// 	'type'  => 'hidden',
		// 	'class' => 'form-control',
		// 	'value' => ''
		// );
		// $this->data['hideBua2'] = array(
		// 	'name'  => 'hideBua2',
		// 	'id'    => 'hideBua2',
		// 	'type'  => 'hidden',
		// 	'class' => 'form-control',
		// 	'value' => ''
		// );
		// $this->data['hideBua3'] = array(
		// 	'name'  => 'hideBua3',
		// 	'id'    => 'hideBua3',
		// 	'type'  => 'hidden',
		// 	'class' => 'form-control',
		// 	'value' => ''
		// );
		// $this->data['hideBua4'] = array(
		// 	'name'  => 'hideBua4',
		// 	'id'    => 'hideBua4',
		// 	'type'  => 'hidden',
		// 	'class' => 'form-control',
		// 	'value' => ''
		// );
		// $this->data['hideBua5'] = array(
		// 	'name'  => 'hideBua5',
		// 	'id'    => 'hideBua5',
		// 	'type'  => 'hidden',
		// 	'class' => 'form-control',
		// 	'value' => ''
		// );

		//Load danh sach thuc an
		// $this->data['lstFoods'] = $this->Foods_model->foods()->result();
		//innit cache drive (300=5 minutes)
		$iInterval=3600;	//60 phut
		$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
		//get foods from cache
		$lstFoods=$this->cache->get('foods'); 
		$lstHtmlFoods=$this->cache->get('foodsHtml'); 
	
		if(!isset($lstFoods) || empty($lstFoods)){
			//Load danh sach thuc an from db
			$lstFoods= $this->Foods_model->foods()->result();
			// Save into the cache for 5 minutes
			$this->cache->save('foods', $lstFoods, $iInterval);
		}
		// $this->data['cachefoods'] ='yescache';
		if(!isset($lstHtmlFoods) || empty($lstHtmlFoods)){
			// $this->data['cachefoods'] ='nocache';
			if(isset($lstFoods) && !empty($lstFoods)){
				foreach ($lstFoods as $values){
					$lstHtmlFoods.='<tr id="' . $values->iIDThucAn . '">';
					$lstHtmlFoods.='<td class="vcenter"><input type="checkbox" name="ThucAn[]" id="chk_' . $values->iIDThucAn . '" value="1" /></td>';
					$lstHtmlFoods.='<td style="display: none">' . $values->iIDThucAn . '</td>';
					$lstHtmlFoods.='<td>' . htmlspecialchars($values->sTenThucAn, ENT_QUOTES, 'UTF-8') . '</td>';
					$lstHtmlFoods.='<td>' . htmlspecialchars($values->sSLplusDVT, ENT_QUOTES, 'UTF-8') . '</td>';
					$lstHtmlFoods.='<td class="col-number">' . round($values->fCalori * 100) / 100 . '</td>';
					$lstHtmlFoods.='<td class="col-number">' . round($values->fDam * 100) / 100 . '</td>';
					$lstHtmlFoods.='<td class="col-number">' . round($values->fBeo * 100) / 100 . '</td>';
					$lstHtmlFoods.='<td class="col-number">' . round($values->fBotOrDuong * 100) / 100 . '</td>';
					$lstHtmlFoods.='<td class="col-number">' . round($values->fXo * 100) / 100 . '</td>';
					$lstHtmlFoods.='</tr>';
				}
				$this->cache->save('foodsHtml', $lstHtmlFoods, $iInterval);
			}
		}

		$this->data['lstFoods'] =$lstFoods;
		$this->data['lstHtmlFoods'] =$lstHtmlFoods;

        /* Load Template */
        $this->template->admin_render('admin/toolcals/edit', $this->data);
	}
}

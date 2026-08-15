<?php
/**
 * Created by PhpStorm.
 * User: Rozenn
 * Date: 17/12/2018
 * Time: 12:29
 */

class User extends Controller
{
    public function showMyAccount($request)
    {

        $this->renderWithData('render/user/my-account', $this->cURL(API.'person/display/'.$_SESSION['IDENTIFIER'], 'PHP_CALL', '', 'GET'));
    }

    public function showPhotos($request) {
        $params = array();
        foreach (PERSON_CONNECTED['children'] as $child):
            $result = $this->cURL(API.'media/list/child/'.$child['childId'], 'PHP_CALL', '', 'GET');
            $params['photos'][] = $result;
        endforeach;
        $this->renderWithData('render/user/photos', $params);
    }


    public function showBooklets($request) {
        $params = array();
        $i = 0;
        foreach (PERSON_CONNECTED['children'] as $child):
            $params['child'][$i] = $child;
            $params['child'][$i]['booklets'] = $this->cURL(API.'bookletchild/byChild/'.$child['childId'].'/published', 'PHP_CALL', '', 'GET');

            $i++;
        endforeach;

        $this->renderWithData('render/user/livrets', $params);
    }

    public function showAddChild($request)
    {

        if(isset($request->i))
        {
            $childId = decodeInt($request->i);
            $this->renderWithData('render/user/add-child', $this->cURL(API.'child/display/'.$childId, 'PHP_CALL', '', 'GET'));
        }
        else
        {
            $this->render('render/user/add-child');   
        }

    }
    public function showAddPerson($request)
    {
        if(isset($request->d))
        {
            $this->renderWithData('render/user/add-person', $this->cURL(API.'person/display/'.decodeInt($request->d), 'PHP_CALL', '', 'GET'));

        }
        else
        {
            $this->render('render/user/add-person');   
        }
    }

    public function showHistoric($request)
    {

        use_helper('dates');

        if(isset($request->year) && $request->year != "") {
            $year = $request->year;
        } else {
            $year = date('Y');
        }

        $params['year'] = $year;
        $invoices = $this->cURL(API.'invoice/listByPerson/'.PERSON_CONNECTED['personId'].'/'.$year, 'PHP_CALL', '', 'GET');
        
        $new_invoices = null;

        foreach($invoices as $invoice) {

            $new_invoiceProducts = [];
            foreach($invoice->invoiceProducts as $invoiceProduct) {

                if(key_exists($invoiceProduct->nameFr, $new_invoiceProducts)) {
                    $new_invoiceProducts[$invoiceProduct->nameFr]['quantity']++;

                    if(isset($invoiceProduct->descriptionFr->dates)) {
                        $new_invoiceProducts[$invoiceProduct->nameFr]['description'][$invoiceProduct->descriptionFr->child_name][] = showDate($invoiceProduct->descriptionFr->dates);
                    }

                } else {
                    $new_invoiceProducts[$invoiceProduct->nameFr] = [
                                                                        'product' => $invoiceProduct,
                                                                        'quantity'       => 1
                    ];
                    if(isset($invoiceProduct->descriptionFr->dates)) {
                        $elements = explode('|', $invoiceProduct->descriptionFr->dates) ;
                        $nbElements = count($elements);
                        if($nbElements > 0) {
                            $i = 0;
                            foreach($elements as $element) {
                                if($i == 0) $first = showDate($element);
                                $dateFr[] = showDate($element);
                                $last = showDate($element);
                                $i++;
                            }
                            $datesToShow = implode('|', $dateFr);
                        } else {
                            $datesToShow = showDate($invoiceProduct->descriptionFr->dates);
                        }
                        $new_invoiceProducts[$invoiceProduct->nameFr]['description'][$invoiceProduct->descriptionFr->child_name][] = $datesToShow;
                        if($nbElements > 5) {
                            $new_invoiceProducts[$invoiceProduct->nameFr]['description2'] = $nbElements.' dates du '.$first.' au '.$last;
                        }
                        unset($dateFr);
                    }
                }
                
              
            }
    
            $invoice->invoiceProducts = $new_invoiceProducts;
            $new_invoices[] = $invoice;

        }

        $params['invoices'] = $new_invoices;




        $this->renderWithData('render/user/historic', $params);
    }

    public function showListChild($request)
    {
     $this->render('render/user/list-child');
    }

    public function showListPerson($request)
    {
        $this->render('render/user/list-person');
    }

    public function personUnassociate($request) {
        // Unreachable from the frontend today — assets/js/pages/user/list-person.js
        // calls the "unassociate" feature via Api::sendRequest directly against the
        // API, not through this route. Left as a no-op rather than dumping $request.
        http_response_code(501);
    }

    public function showProfilPerson($request)
    {
        if(isset($request->id)) {
            $this->redirect('utilisateur/profil/d/'.encodeInt($request->id).'/');
        }

        if(isset($request->d))
        {
            $this->renderWithData('render/user/profil-person', $this->cURL(API.'person/display/'.decodeInt($request->d), 'PHP_CALL', '', 'GET'));
        }
        else
        {
            
        }
    }

    public function childAssociated($request) {
        $newProfilId = $request->i;
        $params['newProfil'] = $this->cURL(API.'person/display/'.$newProfilId, 'PHP_CALL', '', 'GET');

        $this->renderWithData('render/user/associate-child', $params);
    }

    public function doAssociation($request) {

        $childIds = $request->childId;
        $personId = $request->personId;

        foreach($childIds as $childId) {
           $result = $this->cURL(API.'person/associateChild/'.$personId.'/'.$childId, 'PHP_CALL', '', 'GET');
        }
        $this->redirect('utilisateur/profils-associes');

    }

    public function showChildProfil($request)
    {
        if(isset($request->i))
        {
            $childId = decodeInt($request->i);
        }
        else
        {
            $childId = $request->id;
        }
        $this->renderWithData('render/user/profil-child', $this->cURL(API.'child/display/'.$childId, 'PHP_CALL', '', 'GET'));

    }

    public function awaitingPaiement($request){
        // retieve awaiting paiement
        $params = [];
        foreach(PERSON_CONNECTED['children'] as $children) {
            $datas = $this->cURL(API.'product/productPersonal/'.$children['childId'], 'PHP_CALL', '', 'GET');
            if($datas) $params['personalProduct'] = 1;
            $productPersos[$children['firstname'].'|'.$children['childId']] = $datas;
            $params['registrationWaitings'][$children['childId']] = $this->cURL(API.'registration/child-list/'.$children['childId'].'/waiting', 'PHP_CALL', '', 'GET');
        }
        $this->renderWithData('render/user/awaiting-paiement', $params, 'GET');
    }

    public function storeDocument($type): bool
    {
        $allowedfileExtensions = ['pdf', 'jpg', 'png', 'jpeg'];

        if(isset($_FILES[$type]) && $_FILES[$type]['error'] == 0) {

            $fileTmpPath = $_FILES[$type]['tmp_name'];
            $fileName = $_FILES[$type]['name'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));

            $allowedMimeTypes = [
                'pdf'  => ['application/pdf'],
                'jpg'  => ['image/jpeg'],
                'jpeg' => ['image/jpeg'],
                'png'  => ['image/png'],
            ];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $realMimeType = finfo_file($finfo, $fileTmpPath);
            finfo_close($finfo);

            if(in_array($fileExtension, $allowedfileExtensions) && in_array($realMimeType, $allowedMimeTypes[$fileExtension])) {

                $filename = date('YmdHis').rand(0, 1000);
                $uploadFileDir = ROOT.'assets/document/'.$filename.'.'.$fileExtension;
                $dest_path = $uploadFileDir;
                $url_link = 'https://energykidsacademy.net/assets/document/'.$filename.'.'.$fileExtension;

                if(move_uploaded_file($fileTmpPath, $dest_path)) {

                    $arrayChildId = [];
                    foreach(PERSON_CONNECTED['children'] as $children) {
                        $arrayChildId[] = $children['childId'];
                    }

                    $ids = implode(',', $arrayChildId);
                    $result = $this->cURL(API.'child/addJustificatif', 'PHP_CALL', ['ids' => $ids, 'url' => $url_link, 'type' => $type], 'POST');
                } else {
                    echo 'Il y a eu une erreur lors du transfert de votre fichier.';
                }
            } else {
                echo 'Upload non autorisé pour les fichiers de ce type.';
            }
        }
        return true;
    }

    /**
     * @param $request
     * @return null
     */
    public function addJustificatif($request)
    {

        foreach(['justificatif', 'qrcode'] as $type) {
            $result = $this->storeDocument($type);
        }

        return $this->redirect('app/home');
    }

    public function removeDocument($request)
    {
        $arrayChildId = [];
        foreach(PERSON_CONNECTED['children'] as $children) {
            $arrayChildId[] = $children['childId'];
        }
        $ids = implode(',', $arrayChildId);
        $result = $this->cURL(API.'child/removeDocument/', 'PHP_CALL', ['ids' => $ids, 'type' => $request->type], 'POST');
        return $this->renderJson($result);
    }
}
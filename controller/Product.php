<?php use_helper("dates");
class Product extends Controller
{
    public function viewProduct($request)
    {

    	if(isset($request->produit))
    	{
    		$elems = explode("-", $request->produit);
    		$idProduct = $elems[0];

        	$this->renderWithData('render/product/display', $this->cURL(API.'product/display/'.$idProduct, 'PHP_CALL', '', 'GET'));

    	}
    	else
    	{
    	}


    }


    public function viewCategory($request)
    {
       $id = $request->id;

      
      $elements = explode(',', $id);
      $i = 0;

      if(isset($request->p)) {
        $pid = decodeInt(trim($request->p));
        $params['pid'] = $pid;
      } else {
        $params['pid'] = null;
      }
     
      foreach($elements as $id) {

          $id = decodeInt(trim($id));
          $category = $this->cURL(API.'category/display/'.$id, 'PHP_CALL', '', 'GET');

          $categoryName = trim($category->name);


          $all = []; 
          foreach ($category->products as $product) {


            if(isset($product->dates[0])) {

              $key = count($product->dates)-1;

              $keyDate = $product->dates[$key];


              $currentDate = date('Ymd');

              $firstDate = str_replace('-', '', $keyDate);
  
              if($firstDate > $currentDate) {
                $showProduct = 1;
              } else {
                $showProduct = 0;
              }

            } else {
              $showProduct = 1;
            }
            if( ($product->visibility == "frontVisibility" || $product->visibility == "frontvisibility" || $product->visibility == "full") && $showProduct == 1) {
              
              if(in_array($categoryName, ['EKA-SCHOOL', 'EKA-SCHOOL-YEAR', 'EKA-COMPETITION', 'EKA-ENGLISH-PACK', 'EKA-PACKWEEK'])) {
                $key = $product->start_key . "01";
                $key = $this->generateKey($key, $all);
              } else {
                $key = $product->nameFr;
              }

              $all[$key] = $product; // classement par date
            } else {
              $error[] = $product;
            }
          }
          ksort($all);      
          $params['categorys'][$category->publicName] = ['products' => $all, 'photo' => $category->photo, 'name' => $category->name];

      }


      $this->renderWithData('render/product/category', $params);

    }

    private function generateKey($key, $arr) {
      
        if(!key_exists($key, $arr)) return $key;
        $key = $key +1;
        return $this->generateKey($key, $arr);

    }

    public function viewALaCarte($request)
    {
       // On récupère les produits DayCamp
       $id = $request->id;
       $id = decodeInt($id);
       $category = $this->cURL(API.'category/display/'.$id, 'PHP_CALL', '', 'GET');
       $params['categoryId'] = $request->id;
       $i = 0;

       foreach ($category->products as $product) {

            if($product->visibility == "frontVisibility" || $product->visibility == "frontvisibility")
            {
                $params['product'][$i] = $product;

                $i++;
            }

       }

      $this->renderWithData('render/product/alacarte', $params);


    }

    public function countCart($request)
    {


        $regs = $this->cURL(API.'registration/list/'.PERSON_CONNECTED['personId'].'/cart', 'PHP_CALL', '', 'GET');

        $i = 0;
        foreach($regs as $reg) {
          $i++;
        }

        $count = $i;

        echo json_encode(["nbRegistration" => $count]);


    }


    public function viewCart($request)
    {
        if(!isset($_SESSION['TOKEN'])) {
          header("Refresh:0");
        } 

        $currentDate = date('His');


        if(!isset($_SESSION['invoice_id']) || $_SESSION['invoice_id'] == "") {
          // create registration status cart
          $datas = array(
            'person' => PERSON_CONNECTED['personId'],
            'status' => 'cart',
            'nameFr' => PERSON_CONNECTED['firstname'].' '.PERSON_CONNECTED['lastname'],
            'nameEn' => PERSON_CONNECTED['firstname'].' '.PERSON_CONNECTED['lastname'],
            'number' => 'EKA-'.date('ymd').'-'.$currentDate
          );

          $response = $this->cURL(API.'invoice/create', 'AJAX_CALL', $datas, 'POST');

          $_SESSION['invoice_id'] = $response->invoice->invoiceId;
          $invoice = $response->invoice;

        } else {
          $invoice = $this->cURL(API.'invoice/display/'.$_SESSION['invoice_id'], 'AJAX_CALL', null, 'GET');          
        }
       

        $mode = "";

        $adminIdList = [18, 1];

        if(in_array(PERSON_CONNECTED['personId'], $adminIdList)) {
          $phase = "TEST";
        } else {
          $phase = "PRODUCTION";
        }

        $priceTotal = 0;
        $address = "";
        $country = "";
        $phone = "";
        $town = "";
        $postal = "";
        $invoiceId = $invoice->invoiceId; // create invoice "cart"
        $params['cart'] = $this->cURL(API.'registration/list/'.PERSON_CONNECTED['personId'].'/cart', 'PHP_CALL', '', 'GET');
        $paramsRegistrationsIds = "";
        $i = 0;

        $myCart = null;
        $productArr = null;

        foreach ($params['cart'] as $cart) {
            $i++;
            $priceTotal += $cart->product->priceTtc;
            $paramsRegistrationsIds = $paramsRegistrationsIds.$cart->registrationId.',';

            // update registration with invoide id
            $datas = array(
              'invoice' => $invoiceId,
            );
            $response = $this->cURL(API.'registration/modify/'.$cart->registrationId, 'AJAX_CALL', $datas, 'PUT');

            $j = 0;

            if(isset($cart->sessions)) 
            {

                foreach($cart->sessions as $date){
                  if($j == 0) $first = showDate($date->date);
                  $last = showDate($date->date);
                  $alldates[] = showDate($date->date);
                  $j++;
                };

                if( count($alldates) > 5 ) {
                  $datesText = "du ".$first." au ".$last;
                } else {
                  $datesText = implode(' | ', $alldates);
                }
            } else {
              $datesText = null;
              $alldates = [];
            }
              
            if($cart->sports) {
              foreach($cart->sports as $sport){
                $allsports[] = $sport->name;
              };
            } else {
              $allsports = [];
            }

            if(isset($cart->location)) {
              $location_name = $cart->location->name;
            } else {
              $location_name = null;
            }

            ($cart->product->transport == true) ? $transport = "Transport aller-retour" : $transport = "";
         
            $productArr[$cart->child->childId][$cart->product->nameFr][] = [
                                                                              'dates' => implode(',', $alldates),
                                                                              'datesText' => $datesText,
                                                                              'sports' => implode(',', $allsports),
                                                                              'transport' => $transport,
                                                                              'localisation' => $location_name,
                                                                              'name' => $cart->product->nameFr,
                                                                              'amount' => $cart->product->priceTtc,
                                                                              'registrationId' => $cart->registrationId,
                                                                              'description' => $cart->product->descriptionFr
                                                                              
                                                                            ];

            unset($alldates, $allsports);

            $myCart[$cart->child->childId] = $cart->child;

        };
        

        $params['myCart'] = $myCart;
        $params['myProducts'] = $productArr;

        //dd($productArr);



        $paramsRegistrationsIds = substr($paramsRegistrationsIds, 0, -1);
        $params['registrationIds'] = $paramsRegistrationsIds;
        $params['nbOfRegistrations'] = $i;

        if($phase == 'TEST') {
          $key = "9631507820461153";
          $params['systemPay']['vads_ctx_mode']      = "TEST";
        } else {
          $key = "6184019373923758"; // certificat utilisé en prod
          $params['systemPay']['vads_ctx_mode']      = "PRODUCTION";
        }


        if(count(PERSON_CONNECTED['addresses']) != 0)
        {
          $address = PERSON_CONNECTED['addresses'][0]['address'];
          $country = PERSON_CONNECTED['addresses'][0]['country'];
          $town = PERSON_CONNECTED['addresses'][0]['town'];
          $postal = PERSON_CONNECTED['addresses'][0]['postal'];
        }

        if(count(PERSON_CONNECTED['phones']) != 0)
        {
          $phone = PERSON_CONNECTED['phones'][0]['phone'];
        }


        $params['systemPay']['vads_site_id']       = "53151622";
        $params['systemPay']['vads_amount']        = $priceTotal*100;
        $params['systemPay']['vads_currency']      = "978";
        $params['systemPay']['vads_page_action']   = "PAYMENT";
        $params['systemPay']['vads_action_mode']   = "INTERACTIVE";
        $params['systemPay']['vads_cust_email']    = PERSON_CONNECTED['email'];
        $params['systemPay']['vads_cust_address']  = $address;
        $params['vads_cust_country']               = $country;
        $params['systemPay']['vads_cust_name']     = PERSON_CONNECTED['lastname'];
        $params['vads_cust_phone']                 = $phone;
        $params['systemPay']['vads_cust_city']     = $town;
        $params['systemPay']['vads_cust_zip']      = $postal;
        $params['systemPay']['vads_order_info']    = 'EKA-'.date('ymdHi').'-'.PERSON_CONNECTED['personId'];
        $params['systemPay']['vads_order_id']      = $invoiceId; // id de la facture
        $params['systemPay']['vads_cust_id']       = PERSON_CONNECTED['identifier'];
        $params['systemPay']['vads_trans_date']    = date('YmdHis');
        $params['systemPay']['vads_order_info2']   = PERSON_CONNECTED['personId']; // id si order_id

        $lang_code = 'fr';

        $params['systemPay']['vads_language']      = $lang_code;


        if($mode == 'MULTIPLE' || $mode == 'MULTIPLE-NO-TRANSPORT')
        {
          $params['systemPay']['vads_payment_config']= "MULTI:first=".$first.";count=3;period=30";
        } else
        {
          $params['systemPay']['vads_payment_config']= "SINGLE";
        }

        $params['systemPay']['vads_order_info3']   = $phase;
        $params['systemPay']['vads_version']       = "V2";
        $ts = time();
        $params['systemPay']['vads_trans_date']    = gmdate("YmdHis", $ts);
        $params['systemPay']['vads_trans_id']      = $currentDate;

        ksort($params['systemPay']);
        $contenu_signature = "";
        foreach ($params['systemPay'] as $nom => $valeur) {
          $contenu_signature .= $valeur."+";
        }

        $contenu_signature  .= $key;
        $params['systemPay']['signature'] = sha1($contenu_signature);


        $params['number'] = $invoiceId;
        $params['priceTotal'] = $priceTotal;

        // update priceTotal in invocie
      
        $datas = array(
          'priceTtc' => $priceTotal
        );
        $response = $this->cURL(API.'invoice/modify/'.$invoiceId, 'AJAX_CALL', $datas, 'PUT');

        $this->renderWithData('render/product/cart', $params);
    }


    public function viewProductALaCarte($request)
    {
        $idProduct = $request->id;
        $this->renderWithData('render/product/display-a-la-carte', $this->cURL(API.'product/display/'.$idProduct, 'PHP_CALL', '', 'GET'));
    }

    public function createRegistration($request) {
      $invoiceId = $request->invoiceId;

      unset($_SESSION['invoice_id']);

      $datas = array(
        'status' => "paiementInProgress",
      );
      $response = $this->cURL(API.'invoice/modify/'.$invoiceId, 'AJAX_CALL', $datas, 'PUT');
    }

    public function directAddProduct($request) {
        // Not wired up in the frontend (no caller found) — left as a no-op
        // rather than dumping $request.
        http_response_code(501);
    }


}

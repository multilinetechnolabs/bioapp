<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

use Session;

class UspsController extends Controller
{
    public function index(Request $request)
    {
        $shipping_zip = $request->shipping_zip;
        $get_shipping_day = $request->shipping_day_set;
        $scart = Session::get('cart');
        $weight = $scart['weight'];
        $quantity = $scart['quantity'];
        $totalWeight = $weight * $quantity;

        //check over weight
        if ($totalWeight > 1120) {
            echo 'usps limit over';
            exit;
        } else {
            if ($get_shipping_day == 1) {
                //default mail take time 1-3 days weight limit 13oz
                $shipping_day_set = 'First Class';
                if ($totalWeight > 13) {
                    // take time 1-3 days weight limit 70lbs
                    $shipping_day_set = 'Priority';
                }
            } else {
                // take time 1 day weight limit 70lbs
                $shipping_day_set = 'Priority Mail Express';
            }
        }


        //define service

        $devurl = "http://production.shippingapis.com/ShippingAPI.dll";
      
        $service = 'RateV4';
      
        $country = 'philippines';
        $xml = rawurlencode('<RateV4Request USERID="248TBL002177">	
            <Revision>2</Revision>
                  <Package ID="0"> 
                <Service>'.$shipping_day_set.'</Service>	
                <FirstClassMailType>PACKAGE SERVICE RETAIL</FirstClassMailType> 
                <ZipOrigination>91601</ZipOrigination> 
                <ZipDestination>'.$shipping_zip.'</ZipDestination>   
                <Pounds>0</Pounds>  
                <Ounces>'.$totalWeight.'</Ounces>  
                <Container>VARIABLE</Container>  
                <Size>Regular</Size>
                <Width>0</Width> 
                <Length>0</Length>
                <Height>0</Height>
                <Girth>0</Girth> 
                <Content>
                <ContentType>LIVES</ContentType> 
                <ContentDescription>OTHER</ContentDescription> 
                </Content>  
                <Machinable>TRUE</Machinable> 
                </Package>
            </RateV4Request>');
      
        $request = $devurl . "?API=" . $service . "&xml=" . $xml;
        $session = curl_init();
    
        curl_setopt($session, CURLOPT_URL, $request);
        curl_setopt($session, CURLOPT_HTTPGET, 1);
        curl_setopt($session, CURLOPT_HEADER, false);
        curl_setopt($session, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($session, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($session, CURLOPT_HTTPHEADER, array('Accept: application/xml', 'Content-Type: application/xml'));
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
      
        $response = curl_exec($session);
        curl_close($session);
      
        $xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $arr = json_decode($json, true);
        if (isset($arr['Package']['Postage']['Rate'])) {
            if ($shipping_day_set == 'First Class') {
                $CommitmentName ='Null';
            } else {
                $CommitmentName =$arr['Package']['Postage']['CommitmentName'];
            }
            $shipping_rate = array(
          "will_shipping" => $CommitmentName,
          "shipping_rate" => $arr['Package']['Postage']['Rate'],
          "shipping_service" => $shipping_day_set,
          "shipping_zip" => $shipping_zip,
        );
      
            Session::put('rate', $shipping_rate);
        }

        return $arr;
    }
}

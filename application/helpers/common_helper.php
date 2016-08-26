<?php

function getUserId() {
    $ci = &get_instance();
    $userId = $ci->customsession->getUserId();
    if ($userId) {
        return $userId;
    }
}

//To encrypt the password 
function encryptPassword($passwordString) {
    return md5($passwordString);
}

function commonHelperGetPageUrl($pageName, $params = "", $getParams = "") {
    $pageUrls = array();
    $pageSiteUrl = site_url();
    $pageUrls['home'] = $pageSiteUrl.'home';
    $pageUrls['signup'] = $pageSiteUrl.'signup/';
    $pageUrls['login'] = $pageSiteUrl.'login';
    $pageUrls['dashboard'] = $pageSiteUrl.'dashboard';
    $pageUrls['logout'] = $pageSiteUrl.'logout';
    $params = str_replace('&', '/', $params);
    $return = (isset($pageUrls[$pageName])) ? $pageUrls[$pageName] : $pageUrls['home'];
    $return.= (strlen($params) > 0) ? str_replace("&", "/", $params) : "";
    if (strlen($getParams) > 0) {
        $return.= $getParams;
    }
    return $return;
}

// Function to get the client IP address
function commonHelperGetClientIp() {
    $ipAddress = '';
    if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipAddress = getenv('HTTP_X_FORWARDED_FOR');
    else
        $ipAddress = $_SERVER['REMOTE_ADDR'];
    return $ipAddress;
}

//1.->If Date is given,'specified date' converts into specified format type, 
//2.->If Date is not given,'current time' will be given in the specified format type
function allTimeFormats($inputDate, $formatType) {
    switch ($formatType) {
        case 1:
            if ($inputDate) {
                $formattedTime = date('m/d/Y', strtotime($inputDate));
            } else {
                $formattedTime = date('m/d/Y');
            }break;
        case 2:
            if ($inputDate) {
                $formattedTime = date('g:i A', strtotime($inputDate));
            } else {
                $formattedTime = date('g:i A');
            }break;
        case 3: if ($inputDate) {
                $formattedTime = date('l\, jS M Y', strtotime($inputDate));
            } else {
                $formattedTime = date('l\, jS M Y');
            }break;
        case 4: if ($inputDate) {
                $formattedTime = date('h:i A', strtotime($inputDate));
            } else {
                $formattedTime = date('h:i A');
            }break;
        case 6: if ($inputDate) {
                $formattedTime = date('Y-m-d 00:00:00', strtotime($inputDate));
            } else {
                $formattedTime = date('Y-m-d 00:00:00');
            }break;
        case 7: if ($inputDate) {
                $formattedTime = date('d M Y, h:i A', strtotime($inputDate));
            } else {
                $formattedTime = date('d M Y, h:i A');
            }break;
        case 8: if ($inputDate) {
                $formattedTime = date('F j, Y', strtotime($inputDate));
            } else {
                $formattedTime = date('F j, Y');
            }break;
        case 9: if ($inputDate) {
                $formattedTime = date('Y-m-d', strtotime($inputDate));
            } else {
                $formattedTime = date('Y-m-d');
            }break;
        case 11: if ($inputDate) {
                $formattedTime = date('Y-m-d H:i:s', strtotime($inputDate));
            } else {
                $formattedTime = date('Y-m-d H:i:s');
            }break;
        case 12: if ($inputDate) {
                $formattedTime = date('H:i:s', strtotime($inputDate));
            } else {
                $formattedTime = date('H:i:s');
            }break;
        case 14: if ($inputDate) {
                $formattedTime = date('Ymd', strtotime($inputDate));
            } else {
                $formattedTime = date('Ymd');
            }break;
        case 15: if ($inputDate) {
                $formattedTime = date('F d, Y', strtotime($inputDate));
            } else {
                $formattedTime = date('F d, Y');
            }break;
        case 16: if ($inputDate) {
                $formattedTime = date('h:i a', strtotime($inputDate));
            } else {
                $formattedTime = date('h:i a');
            }break;
        case 17: if ($inputDate) {
                $formattedTime = date('Y', strtotime($inputDate));
            } else {
                $formattedTime = date('Y');
            }break;
        case 18: if ($inputDate) {
                $formattedTime = date('F d, Y,h:i A', strtotime($inputDate));
            }else {
                $formattedTime = date('F d, Y');
            }break;
		case 19: if ($inputDate) {
					$formattedTime = gmdate('Y-m-d\TH:i:s.u\Z', strtotime($inputDate));
				} else {
					$formattedTime = gmdate('Y-m-d\TH:i:s.u\Z', strtotime(date('Y-m-d H:i:s.u')));
				}break;
    }
    return $formattedTime;
}

/**
 * If admin logied in we send admin user id othe wise we will
 *  send user related session info
 */
function getSessionUserId(){
    $ci = &get_instance();
    $adminId=$ci->customsession->getData("adminId");
    if ($adminId) {
        return $adminId;
    }
    return getUserId();       
}

function commonHelperGetIdArray($input, $groupByKey = 'id') {
    $returnArray = array();
    if (count($input) > 0) {
        foreach ($input as $key => $val) {
            $keyname = $val[$groupByKey];
            foreach ($val as $id => $value) {
                if ($id == $groupByKey)
                    $keyname = $value;
                $returnArray[$keyname][$id] = $value;
            }
        }
    }
    return $returnArray;
}

?>


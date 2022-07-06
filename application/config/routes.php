<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
//$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//login
$route['default_controller'] = 'login/index';
$route['auth'] = 'login/auth';
$route['auth/inline'] = 'login/authInline';
$route['main'] = 'login/main';
$route['logout'] = 'login/logout';

$route['hq'] = 'hq/hq_login/index';
$route['sess'] = 'login/sess';
$route['walikan/(:any)'] = "welcome/formWalikan/$1";



/*------------------------
 * API
 * ----------------------*/
 //$route['memb/log'] = 'backend_api/membLogin';
$route['db2/get/(:any)/where/(:any)/(:any)'] = "backend_api/getDataWithSpecificField/$1/$2/$3";
$route['db1/list/from/(:any)/(:any)/(:any)'] = "backend_api/getRetrieveAllFieldB1/$1/$2/$3";
$route['db2/get/(:any)/from/(:any)/(:any)/(:any)'] = "backend_api/getRetrieveFieldDB2/$1/$2/$3/$4";
$route['api/member/check/(:any)'] = "backend_api/checkValidIdMember/$1";
$route['api/member/double/(:any)/(:any)'] = "backend_api/memberCheckExistingRecordByField/$1/$2";
$route['api/test'] = "backend_api/tesAPI";
$route['get/random/member'] = "backend_api/get100Member";
/*-------------------------------
 * APPLICATION MAINTENANCE ADMIN
 * ------------------------------ */
$route['app'] = 'userconfig/userconfig/getInputApplication';
$route['app/id/(:any)'] = 'userconfig/userconfig/getListApplicationByID/$1';
$route['app/list'] = 'userconfig/userconfig/getListAllApplication';
$route['app/list/json'] = 'userconfig/userconfig/getListAllApplication/$1';
$route['app/list/(:any)/(:any)'] = 'userconfig/userconfig/getListApplication/$1/$2';
$route['app/save'] = 'userconfig/userconfig/saveInputApplication';
$route['app/update'] = 'userconfig/userconfig/saveUpdateApplication';
$route['app/delete/(:any)'] = 'userconfig/userconfig/deleteApplication/$1';

/*--------------------------
 * USER MAINTENANCE ADMIN
 * ------------------------ */
$route['user'] = 'userconfig/userconfig/getInputUser';
$route['user/id/(:any)'] = 'userconfig/userconfig/getListUserByID/$1';
$route['user/save'] = 'userconfig/userconfig/saveInputUser';
$route['user/list'] = 'userconfig/userconfig/getListAllUser';
$route['user/param/(:any)/(:any)'] = 'userconfig/userconfig/getListAllUserByParam/$1/$2';
$route['user/list/(:any)/(:any)'] = 'userconfig/userconfig/getListUser/$1/$2';
$route['user/update'] = 'userconfig/userconfig/saveUpdateUser';
$route['user/delete/(:any)'] = 'userconfig/userconfig/deleteUser/$1';
$route['user/prevbns/update'] = 'userconfig/userconfig/updatePrevBonus';

/*--------------------------
 * USER GROUP MAINTENANCE ADMIN
 * ------------------------ */
$route['user/group'] = 'userconfig/userconfig/getInputUserGroup';
$route['user/group/id/(:any)'] = 'userconfig/userconfig/getListUserGroupByID/$1';
$route['user/group/list'] = 'userconfig/userconfig/getListAllUserGroup';
$route['user/group/list/json'] = 'userconfig/userconfig/getListAllUserGroup/$1';
$route['user/group/list/(:any)/(:any)'] = 'userconfig/userconfig/getListUserGroup/$1/$2';
$route['user/group/save'] = 'userconfig/userconfig/saveInputUserGroup';
$route['user/group/update'] = 'userconfig/userconfig/saveUpdateUserGroup';
$route['user/group/delete/(:any)'] = 'userconfig/userconfig/deleteUserGroup/$1';

/*--------------------------
 * GROUP MENU MAINTENANCE ADMIN
 * ------------------------ */
$route['menu/group'] = 'userconfig/userconfig/getInputGroupMenu';
$route['menu/group/list'] = 'userconfig/userconfig/getListAllGroupMenu';
$route['menu/group/list/json'] = 'userconfig/userconfig/getListAllGroupMenu/$1';
$route['menu/group/id/(:any)'] = 'userconfig/userconfig/getListGroupMenuByID/$1';
$route['menu/group/list/(:any)/(:any)'] = 'userconfig/userconfig/getListGroupMenu/$1/$2';
$route['menu/group/save'] = 'userconfig/userconfig/saveInputGroupMenu';
$route['menu/group/update'] = 'userconfig/userconfig/saveUpdateGroupMenu';
$route['menu/group/delete/(:any)'] = 'userconfig/userconfig/deleteGroupMenu/$1';

/*--------------------------
 * SUB MENU MAINTENANCE ADMIN
 * ------------------------ */
$route['menu'] = 'userconfig/userconfig/getInputSubMenu';
$route['menu/list'] = 'userconfig/userconfig/getListAllSubMenu';
$route['menu/list/json'] = 'userconfig/userconfig/getListAllSubMenu/$1';
$route['menu/id/(:any)'] = 'userconfig/userconfig/getListSubMenuByID/$1';
$route['menu/list/(:any)/(:any)'] = 'userconfig/userconfig/getListSubMenu/$1/$2';
$route['menu/save'] = 'userconfig/userconfig/saveInputSubMenu';
$route['menu/update'] = 'userconfig/userconfig/saveUpdateSubMenu';
$route['menu/delete/(:any)'] = 'userconfig/userconfig/deleteSubMenu/$1';

/*--------------------------
 * USER GROUP PRIVELEDGE MENU
 * ------------------------ */
$route['menu/access'] = 'userconfig/userconfig/getInputAccessMenu';
$route['menu/check'] = 'userconfig/userconfig/getShowListMenuByGroupID';
$route['menu/access/save'] = 'userconfig/userconfig/saveInputAccessMenu';

$route['password/change'] = 'userconfig/userconfig/changePassword';
$route['password/change/save'] = 'userconfig/userconfig/saveChangePassword';


/*--------------------------
 * DISTRIBUTOR TRANSACTION
 * ------------------------ */
$route['member/trx'] = 'transaction/sales_member/memberTrxSearch';
$route['member/trx/search'] = 'transaction/sales_member/memberTrxSearchResult';
$route['member/trx/detail/(:any)/(:any)'] = 'transaction/sales_member/memberTrxDetailProductByID/$1/$2';
/*------------------------------
 * STOCKIST PERSONAL PARTICULAR
 * --------------------------- */
$route['stockist/addr'] = 'stockist/stockist/formUpdateAddrStk';
$route['stockist/addr/update'] = 'stockist/stockist/saveUpdateAddrStk';
$route['stockist/info'] = 'stockist/stockist/formStockistInfo';
$route['stockist/id/(:any)'] = 'stockist/stockist/getDetailStockistByID/$1';
$route['stockist/kabupaten/list/(:any)'] = 'stockist/stockist/listKabupatenByProvince/$1';
$route['stockist/kecamatan/list/(:any)'] = 'stockist/stockist/listKecamatanByKabupaten/$1';
$route['stockist/kelurahan/list/(:any)'] = 'stockist/stockist/listKelurahannByKecamatan/$1';
$route['stockist/kodepos/(:any)'] = 'stockist/stockist/showKodepos/$1';

/*------------------------------
 * PRODUCT
 -------------------------------*/
$route['product/search'] = 'product/product/productSearch';
$route['product/search/list'] = 'product/product/productSearchByParam';
$route['product/id/(:any)/(:any)'] = 'product/product/productSearchByID/$1/$2';
$route['product/bundling'] = 'product/product/check_kode_bundlingFrm';

$route['product/bundling/checkCode'] = 'product/product/formCheckBundling';
$route['product/bundling/list/(:any)'] = 'product/product/listDetailBundle/$1';
$route['product/bundling/code'] = 'product/product/searchBundlingCode';
/*------------------------------
 * MEMBER
 -------------------------------*/
$route['member/reg'] = 'member/member_registration/regMember';
$route['member/voucher/check/(:any)/(:any)'] = 'member/member_registration/checkVoucher/$1/$2';
$route['member/reg/input'] = 'member/member_registration/inputMember';
$route['member/list/stk/(:any)'] = 'member/member_registration/showStockistByArea/$1';
$route['member/reg/input/save'] = 'member/member_registration/saveInputMember';
$route['member/reg/couple'] = 'member/member/regMemberCouple';
$route['member/reg/id/(:any)'] = 'member/member_registration/showNewMember/$1';

$route['member/sk/promo'] = 'member/member_registration/formBeliSk';
$route['member/checkID/(:any)'] = 'member/member_registration/checkIdMember/$1';
$route['member/checkProduct'] = 'member/member_registration/checkProduct';
$route['member/sk/promo/save'] = 'member/member_registration/saveBeliSk';
/*------------------------------
 * MEMBER REPORT & SEARCH
 -------------------------------*/
$route['member/search'] = 'member/member_report/searchMember';
$route['member/search/list'] = 'member/member_report/searchMemberList';
$route['member/id/(:any)'] = 'member/member_report/getMemberByID/$1';

/*----------------------
 * LBC MEMBER
 * --------------------*/
$route['lbc'] = 'member/lbc/regLbcMember';
$route['lbc/id/(:any)'] = 'member/lbc/checkLbcByID/$1';
$route['lbc/save'] = 'member/lbc/saveRegLbc';
$route['lbc/report'] = 'member/lbc/lbcReport';
$route['lbc/report/list'] = 'member/lbc/lbcReportList';

/*--------------------------
 * INCOMING PAYMENT UPDATE
 * -------------------------*/
$route['incoming/update'] = 'transaction/incoming_payment/formUpdateIncomingPayment';
$route['incoming/detail'] = 'transaction/incoming_payment/getDetailIncomingPayment';
$route['incoming/fullname/(:any)/(:any)'] = 'transaction/incoming_payment/getDetailFullname/$1/$2';
$route['incoming/update/save'] = 'transaction/incoming_payment/saveUpdateIncomingPayment';
/*--------------------------------
 * SALES INPUT, GENERATE & REPORT
 * ------------------------------*/
$route['sales/report/export'] = 'transaction/sales_stockist/reportToExcel';
$route['sales/stk/ttp/input'] = 'transaction/sales_stockist/inputTTP';
$route['sales/stk/input/list'] = 'transaction/sales_stockist/getListInputSalesStockist';
$route['sales/stk/input/report'] = 'transaction/sales_stockist/reportGenerated';
$route['sales/stk/update/(:any)/(:any)'] = 'transaction/sales_stockist/updateTrx/$1/$2';
$route['sales/stk/input/form'] = 'transaction/sales_stockist/inputTrxForm';
$route['sales/stk/info/(:any)'] = 'transaction/sales_stockist/getStockistInfo/$1';
$route['sales/stk/save'] = 'transaction/sales_stockist/saveTrxStockist';
$route['sales/stk/delete/(:any)/(:any)'] = 'transaction/sales_stockist/deleteTrx/$1/$2';

$route['sales/vcash2/save'] = 'transaction/sales_stockist/saveVcashVersi2';

$route['sales/vc/check/(:any)/(:any)/(:any)'] = 'transaction/sales_stockist/checkValidVoucherCash/$1/$2/$3';
$route['sales/sub/ttp/input'] = 'transaction/sales_stockist/inputTtpSub';
//$route['sales/sub/input/list'] = 'transaction/sales_stockist/getListInputSalesStockist';
//$route['sales/sub/update/(:any)/(:any)'] = 'transaction/sales_stockist/updateTrx/$1/$2';
$route['sales/sub/input/form'] = 'transaction/sales_stockist/inputTrxFormSub';
$route['sales/sub/input/formV2/(:any)/(:any)'] = 'transaction/sales_stockist/inputTrxFormSubV3/$1/$2';
//$route['sales/sub/save'] = 'transaction/sales_stockist/saveTrxStockist';

$route['sales/pvr2/input/form'] = 'transaction/sales_stockist/inputTrxPvrForm2';
$route['sales/product/pvr/check'] = 'transaction/sales_stockist/showProductPriceForPvr';
$route['sales/pvr2/save'] = 'transaction/sales_stockist/savePvrVersi2';

$route['sales/sub/input/vcash'] = 'transaction/sales_stockist/inputVchCash';

$route['sales/input'] = 'transaction/sales_stockist/inputSales';
$route['sales/input/save'] = 'transaction/sales_stockist/saveInputSales';

$route['sales/generate'] = 'transaction/sales_generate/formGenerateScoTrx';
$route['sales/search/list'] = 'transaction/sales_generate/searchUngeneratedSales';
$route['sales/search/list/detail'] = 'transaction/sales_generate/getdetail';
$route['sales/search/list/checkSelisih'] = 'transaction/sales_generate/checkSelisih';
$route['sales/detail/(:any)/(:any)/(:any)/(:any)/(:any)'] = 'transaction/sales_generate/getDetailSales/$1/$2/$3/$4/$5';
$route['sales/generate/preview'] = 'transaction/sales_generate/previewGenerate';
$route['sales/generate/sales'] = 'transaction/sales_generate/generateSales';
$route['sales/generate/salesV2'] = 'transaction/sales_generate/generateSalesV2';
$route['sales/generate/update-bonus-period'] = 'transaction/sales_generate/updateBonusPeriod';


$route['sales/pvr/input'] = 'transaction/sales_stockist/inputSalesPvr';
$route['sales/pvr/input/list'] = 'transaction/sales_stockist/getListInputPvrSalesStockist';
$route['sales/pvr/input/report'] = 'transaction/sales_stockist/reportPvrGenerated';
$route['sales/pvr/input/form'] = 'transaction/sales_stockist/inputTrxPvrForm';
$route['sales/pvr/input/save'] = 'transaction/sales_stockist/saveInputSalesPvr';

$route['sales/pvr/updatestk'] = 'transaction/sales_stockist/updatePvrStk';
$route['sales/pvr/updatestk/get/(:any)'] = 'transaction/sales_stockist/getInfoPvrStk/$1';
$route['sales/pvr/updatestk/save'] = 'transaction/sales_stockist/saveNewStkPvr';

$route['sales/preview/(:any)/(:any)'] = 'transaction/sales_generate/listTTPbySSR/$1/$2';
$route['sales/correction/(:any)'] = 'transaction/sales_stockist/koreksiTransaksi/$1';

$route['sales/generated/report'] = 'transaction/sales_stockist_report/formListGeneratedSales';
$route['sales/generated/report/list'] = 'transaction/sales_stockist_report/getListGeneratedSales';
$route['sales/generated/ssr-bonus-period'] = 'transaction/sales_trans/formSsrBonusPeriod';
$route['sales/generated/check-ssr'] = 'transaction/sales_trans/checkSsr';
$route['sales/generated/recover-ssr'] = 'transaction/sales_trans/recoverSsr';
$route['sales/generated/change-bonus-period'] = 'transaction/sales_trans/changeBonusPeriod';
$route['sales/generated/ssr/(:any)'] = 'transaction/sales_stockist_report/getDetailTrxBySSR/$1';
$route['sales/reportstk/(:any)/(:any)'] = 'transaction/sales_stockist_report/listTTP/$1/$2';
$route['sales/report/excel'] = 'transaction/sales_stockist_report/ssrExportExcel';

$route['sales/pending/report'] = 'transaction/sales_stockist_report/pendingStk';
$route['sales/pending/report/list'] = 'transaction/sales_stockist_report/pendingStkList';


$route['sales/voucher/report'] = 'transaction/sales_stockist_report/voucherReport';
$route['sales/voucher/report/list'] = 'transaction/sales_stockist_report/voucherReportList';
$route['sales/voucher/no/(:any)'] = 'transaction/sales_stockist_report/getDetailVoucherNo/$1';

$route['sales/payment'] = 'transaction/sales_payment/index';
$route['sales/payment/list'] = 'transaction/sales_payment/getListSalesReport';
$route['sales/payment/ssr/(:any)'] = 'transaction/sales_payment/getDetailTTPbySSRno/$1';
$route['sales/payment/preview'] = 'transaction/sales_payment/previewSelectedSSR';

$route['sales/sub/input/vcashKhusus'] = 'transaction/sales_stockist/inputVchCashKhusus';
$route['sales/vcashKhusus/prdCheck'] = 'transaction/sales_stockist/CheckPrdVchCashKhusus';
$route['sales/vcashKhusus/check'] = 'transaction/sales_stockist/checkValidVoucherCashKhusus';
$route['sales/vcashKhusus/save'] = 'transaction/sales_stockist/saveVchCashKhusus';

$route['sales/report/product'] = 'transaction/sales_stockist_report/rekapSalesProduct';
$route['sales/report/product/list'] = 'transaction/sales_stockist_report/rekapSalesProductList';
$route['sales/report/product/excell'] = 'transaction/sales_stockist_report/rekapSalesProductListExcell';
$route['sales/report/stkssr'] = 'transaction/sales_stockist_report/stkssr';

$route['sales/input/promo'] = 'transaction/sales_stockist/inputProdukPromo';
$route['sales/input/promo/save'] = 'transaction/sales_stockist/saveInputProdukPromo';
$route['sales/promo/check'] = 'transaction/sales_stockist/checkSalesPromo';
$route['sales/promo/checksisa'] = 'transaction/sales_stockist/checkSisa';
$route['sales/promo/resetpromo'] = 'transaction/sales_stockist/ulangiInputFree';
/*---------------------
 * SALES ONLINE REDEMP
 * -------------------*/
 $route['sales/ol/redemp'] = 'transaction/sales_online/formOnlineRedemp';
 $route['sales/ol/redemp/list'] = 'transaction/sales_online/searchOnlineRedemp';
 $route['sales/ol/redemp/report'] = 'transaction/sales_online/reportToExcel';
 $route['sales/ol/redemp/report-product'] = 'transaction/sales_online/reportProduct';
 $route['sales/ol/redemp/export-excel'] = 'transaction/sales_online/exportToExcel';
 $route['sales/ol/orderno/(:any)'] = 'transaction/sales_online/onlineTrxDetail/$1';
 $route['sales/ol/redemp/save'] = 'transaction/sales_online/onlineRedempSave';
 $route['sales/ol/reprint/(:any)'] = 'transaction/sales_online/reprintNote/$1';
 $route['sales/ol/listbns/(:any)'] = 'transaction/sales_online/listBnsMonth/$1';

 /*---------------------
 * SALES ONLINE REPORT
 * -------------------*/
 $route['sales/ol/ip'] = 'transaction/sales_online_report/formListIncomingPayment';
 $route['sales/ol/ip/list'] = 'transaction/sales_online_report/getListIncomingPayment';
 /*------------------------------
  * STOCK BARCODE
  * ----------------------------*/
// $route['stk/barcode'] = 'transaction/stock_barcode/formStockBarcode';
$route['stk/barcode/trace'] = 'transaction/stock_barcode/fromTraceBarcode'; // on dev retna
$route['stock_barcode/postTraceBarcode/(:any)'] = 'transaction/stock_barcode/postTraceBarcode/$1';
$route['stk/barcode'] = 'transaction/stock_barcode/formStockBarcode'; // on dev retna
$route['stock_barcode/detailTransaction'] = 'transaction/stock_barcode/detailTransaction'; // on dev retna
$route['stock_barcode/detailTransactionRange'] = 'transaction/stock_barcode/detailTransactionRange'; // on dev retna
$route['stock_barcode/saveBarcode'] = 'transaction/stock_barcode/saveBarcode'; // on dev retna
$route['stock_barcode/detailScaProduk/(:any)'] = 'transaction/stock_barcode/detailScaProduk/$1'; // on dev retna
$route['stock_barcode/detailTransaction2/(:any)'] = 'transaction/stock_barcode/detailTransaction2/$1'; // on dev retna
$route['stk/barcode/wh/list'] = 'transaction/stock_barcode/getListWH';
$route['stk/barcode/trx/list'] = 'transaction/stock_barcode/getListTrx';
$route['stk/barcode/trx/id/(:any)'] = 'transaction/stock_barcode/getDetailProductByTrxId/$1';
$route['stk/barcode/process/(:any)/(:any)'] = 'transaction/stock_barcode/getListProductBarcode/$1/$2';
$route['stk/barcode/save'] = 'transaction/stock_barcode/saveBarcode';
$route['stk/barcode/prepare/pl'] = 'transaction/stock_barcode/preparePackingList';
$route['stk/barcode/generate/pl'] = 'transaction/stock_barcode/generatePackingList';

$route['stk/barcode/simpan'] = 'transaction/stock_barcode/simpanBarcode';
$route['stk/barcode/check/(:any)/(:any)'] = 'transaction/stock_barcode/getDataFullName/$1/$2';
/*--------------------------
 * WAREHOUSE
 * ------------------------*/

 /*-----------------------
  * VOUCHER RELEASE
  * ---------------------*/
$route['voucher/search'] = 'member/voucher/voucherSearchForm';
$route['voucher/search/list'] = 'member/voucher/voucherSearchResult';
$route['voucher/detail/(:any)'] = 'member/voucher/voucherDetail/$1';
$route['voucher/product/(:any)/(:any)'] = 'member/voucher/detailReleasedSK/$1/$2';
$route['voucher/check/formno/(:any)/(:any)'] = 'member/voucher/checkVoucherNo/$1/$2';
$route['wh/releasevcr/upd/(:any)/(:any)/(:any)'] = 'backend/warehouse/getReleaseVcrSKupd/$1/$2/$3';


$route['voucher/release'] = 'member/voucher/saveReleaseVoucher';

/*-----------------------
  * DO
  * ---------------------*/
  $route['do/stk'] = 'transaction/do_stockist/formGetListDO';
  $route['do/stk/list'] = 'transaction/do_stockist/getListDOStk';
  $route['do/stk/gdo/(:any)'] = 'transaction/do_stockist/listSSRbyGDO/$1';
  $route['do/stk/trx/(:any)/(:any)'] = 'transaction/do_stockist/listTTPbySSR/$1/$2';

/**
 * Scan voucher
 */
$route['scan'] = 'transaction/scan_voucher/formScanDeposit';
$route['scan/list'] = 'transaction/scan_voucher/getDeposit';
$route['scan/list/detail/voucher/(:any)'] = 'transaction/scan_voucher/getListScan/$1';
$route['scan/list/detail/ttp/(:any)'] = 'transaction/scan_voucher/getTTPList/$1';
$route['scan/list/delete'] = 'transaction/scan_voucher/hapusDeposit';
$route['scan/ttp/input/(:any)'] = 'transaction/scan_voucher/getFormTtpDeposit2/$1';
$route['scan/ttp/view/(:any)/(:any)/(:any)'] = 'transaction/scan_voucher/viewTTP/$1/$2/$3';
$route['scan/ttp/delete/(:any)'] = 'transaction/scan_voucher/hapusTtpVchDeposit/$1';
$route['scan/ttp/save'] = 'transaction/scan_voucher/saveTrxDepositVch';
$route['scan/deposit/recalculate/(:any)'] = 'transaction/scan_voucher/recalculateDeposit/$1';
$route['scan/vch/delete'] = 'transaction/scan_voucher/hapusVchCash';

$route['scan/deposit/tescalculate/(:any)'] = 'transaction/scan_voucher/tescalculate/$1';

/*----------
  *   TAX
  * -------*/
$route['tax/print'] = 'stockist/tax';
$route['tax/print/act'] = 'stockist/tax/getTaxStk';
$route['tax/stk/print'] = 'stockist/tax/printTaxToPDF';

/**
 * Release voucher promo stockist
 */
$route['release/vch-stk'] = 'member/voucher/formVchStkActivate';
$route['release/check/vch-stk'] = 'member/voucher/cekVchStk';


$route['wa/sendtemplate/(:any)'] = 'Api_whatsapp/kirimWaTemplate/$1';

$route['reseller'] = 'transaction/reseller/inputTrxReseller';
$route['reseller/newregister'] = 'transaction/reseller/formNewRegister';
$route['reseller/id/(:any)'] = 'transaction/reseller/getDataReseller/$1';
$route['reseller/saveregister'] = 'transaction/reseller/saveRegister';
$route['reseller/search'] = 'transaction/reseller/cariTrxReseller';
$route['reseller/updateInv/(:any)'] = 'transaction/reseller/updateInv/$1';
$route['reseller/listInv/(:any)'] = 'transaction/reseller/listInv/$1';
$route['reseller/listIncPay/(:any)'] = 'transaction/reseller/listIncPay/$1';
$route['reseller/listIncPayV2'] = 'transaction/reseller/listIncPayV2';
$route['reseller/previewInvReseller'] = 'transaction/reseller/previewInvReseller';
$route['reseller/saveInvReseller'] = 'transaction/reseller/saveInvReseller';
$route['reseller/inv/print'] = 'transaction/reseller/printInv';
$route['reseller/product/pvr/check'] = 'transaction/reseller/showProductPriceForPvr';
$route['reseller/produk'] = 'transaction/reseller/listPrd';
$route['reseller/produk/list'] = 'transaction/reseller/listPrdAct';
$route['reseller/produk/all'] = 'transaction/reseller/listAllPrd';
$route['reseller/name/all'] = 'transaction/reseller/listAllReseller';
$route['reseller/name/list'] = 'transaction/reseller/listResellerAct';

$route['payment/receipt'] = 'transaction/payment_receipt/index';
$route['payment/receipt/findregister'] = 'transaction/payment_receipt/findRegister';
$route['payment/receipt/findIncPayByInv'] = 'transaction/payment_receipt/findIncPayByInv';
$route['payment/receipt/save'] = 'transaction/payment_receipt/simpanKW';
$route['payment/print'] = 'transaction/payment_receipt/printKw';

$route['payment/receipt/report'] = 'transaction/payment_receipt/form_report';
$route['payment/receipt/finddata'] = 'transaction/payment_receipt/findData';
$route['payment/receipt/detail/(:any)'] = 'transaction/payment_receipt/getDetailIncPayVc/$1';
$route['payment/receipt/cn/(:any)'] = 'transaction/payment_receipt/getTransByCNno/$1';

$route['payment/receipt/cancel'] = 'transaction/payment_receipt/formCancelPayReceipt';
$route['payment/receipt/cancel/save'] = 'transaction/payment_receipt/saveCancelPayReceipt';


/*--------------------------
 * INCOMING PAYMENT B/0
 * -------------------------*/
$route['inc/pay'] = 'finance/incoming/formIncPayment';
$route['inc/pay/list'] = 'finance/incoming/listIncPay';
$route['inc/pay/save'] = 'finance/incoming/saveIncPay';
$route['inc/pay/update'] = 'finance/incoming/updateIncPay';
$route['inc/pay/id'] = 'finance/incoming/listIncPayById';
$route['inc/pay/form'] = 'finance/incoming/createIncPay';

/*--------------
CN / MS
---------------*/
$route['bo/cnmsn/register'] = 'finance/cnms/formRegister';
$route['bo/cnmsn/list'] = 'finance/cnms/listTrx';
$route['bo/cnmsn/register/save'] = 'finance/cnms/saveRegister';
$route['bo/cnmsn/newregister'] = 'finance/cnms/newregister';
$route['bo/cnmsn/updateInv/(:any)'] = 'finance/cnms/updateInv/$1';
$route['bo/cnmsn/rekapcn/(:any)/(:any)'] = 'finance/cnms/rekapCN/$1/$2';
$route['bo/cnmsn/listIncPayV2'] = 'finance/cnms/listIncPayV2';
$route['bo/cnmsn/incById'] = 'finance/cnms/getIncPayById';
$route['bo/cnmsn/cn/save'] = 'finance/cnms/saveCN';
$route['bo/cnmsn/listInv/(:any)'] = 'finance/cnms/listInv/$1';
$route['bo/cnmsn/id/(:any)'] = 'finance/cnms/viewCn/$1';
$route['bo/cnmsn/edit/(:any)'] = 'finance/cnms/viewCnWithEdit/$1';
$route['bo/cnmsn/formedit/(:any)'] = 'finance/cnms/editTtpManual/$1';
$route['bo/cnmsn/print'] = 'finance/cnms/printCnV';
$route['bo/cnmsn/printv/(:any)'] = 'finance/cnms/printCn/$1';
$route['bo/cnmsn/pvr/approve'] = 'finance/cnms/pvrApprove';

$route['bo/cnmsn/manual'] = 'finance/cnms/cnmsManual';
$route['bo/cnmsn/manual/check/(:any)'] = 'finance/cnms/checkCnManual/$1';
$route['bo/cnms/product/check'] = 'finance/cnms/checkProdukCNManual';
$route['bo/cnmsn/manual/save'] = 'finance/cnms/saveCnMsManual';
$route['bo/cnmsn/manual/update'] = 'finance/cnms/updateCnMSManual';
$route['bo/cnmsn/manual/hapus/(:any)'] = 'finance/cnms/hapusTtpManual';
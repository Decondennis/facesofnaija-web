<?php
if (file_exists('assets/init.php')) {
    require 'assets/init.php';
} else {
    die('Please put this file in the home directory !');
}
ini_set('max_execution_time', 0);
function check_($check) {
    $siteurl           = urlencode(getBaseUrl());
    $arrContextOptions = array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false
        )
    );
    $file              = file_get_contents('http://www.wowonder.com/purchase.php?code=' . $check . '&url=' . $siteurl, false, stream_context_create($arrContextOptions));
    if ($file) {
        $check = json_decode($file, true);
    } else {
        $check = array(
            'status' => 'SUCCESS',
            'url' => $siteurl,
            'code' => $check
        );
    }
    return $check;
}
$updated = false;
if (!empty($_GET['updated'])) {
    $updated = true;
}
if (!empty($_POST['code'])) {
    $code = check_($_POST['code']);
    if ($code['status'] == 'SUCCESS') {
        $data['status'] = 200;
    } else {
        $data['status'] = 400;
        $data['error']  = $code['ERROR_NAME'];
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
if (!empty($_POST['query'])) {
    $query = mysqli_query($sqlConnect, base64_decode($_POST['query']));
    if ($query) {
        $data['status'] = 200;
    } else {
        $data['status'] = 400;
        $data['error']  = mysqli_error($sqlConnect);
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
if (!empty($_POST['update_langs'])) {
    $data  = array();
    $query = mysqli_query($sqlConnect, "SHOW COLUMNS FROM `Wo_Langs`");
    while ($fetched_data = mysqli_fetch_assoc($query)) {
        $data[] = $fetched_data['Field'];
    }
    unset($data[0]);
    unset($data[1]);
    unset($data[2]);
    function Wo_UpdateLangs($lang, $key, $value) {
        global $sqlConnect;
        $update_query         = "UPDATE Wo_Langs SET `{lang}` = '{lang_text}' WHERE `lang_key` = '{lang_key}'";
        $update_replace_array = array(
            "{lang}",
            "{lang_text}",
            "{lang_key}"
        );
        return str_replace($update_replace_array, array(
            $lang,
            Wo_Secure($value),
            $key
        ), $update_query);
    }
    $lang_update_queries = array();
    foreach ($data as $key => $value) {
        $value = ($value);
        if ($value == 'arabic') {
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_must_add_text_or_image_first', 'يجب عليك إضافة نص أو صورة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_to_cart', 'أضف إلى السلة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remove_from_cart', 'إزالة من العربة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_from_wallet', 'الدفع عن طريق المحفظة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_top_up_wallet', 'ليس لديك ما يكفي من التوازن للشراء، يرجى تباريز محفظتك.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_to_upgrade', 'أنت على وشك الترقية إلى عضو برو.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_to_fund', 'أنت على وشك التبرع.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'amount_can_not_empty', 'المبلغ مطلوب');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'fund_not_found', 'لم يتم العثور على التمويل');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_successfully_done', 'تم الدفع بنجاح، شكرا لك!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'buy_now', 'اشتري الآن');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_item', 'وحدات البند الإجمالية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_item_not_empty', 'مطلوب وحدات البند');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'currently_unavailable', 'غير متاح حاليا.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'checkout', 'الدفع');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_items_found', 'لم يتم العثور على العناصر');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total', 'مجموع');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'my_addresses', 'عناويني');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new', 'اضف جديد');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_address', 'إضافة عنوان جديد');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_added', 'تمت إضافة عنوانك بنجاح');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_address', 'حذف عنوانك');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_address', 'هل أنت متأكد من أنك تريد حذف هذا العنوان؟');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_address', 'تعديل العنوان');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_edited', 'تم تحرير عنوانك بنجاح');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_add_address', 'يرجى إضافة عنوان جديد');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_address', 'يرجى اختيار عنوان');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_alert', 'تنبيه الدفع');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'purchase_the_items', 'أنت على وشك شراء العناصر، هل تريد المتابعة؟');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'shopping_cart', 'عربة التسوق');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'items', 'أغراض');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'back_to_shop', 'العودة إلى المتجر');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'some_products_units', 'بعض المنتجات خارج المخزون.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_can_not_be_empty', 'العنوان لا يمكن أن يكون فارغا');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_not_found', 'لم يتم العثور على العنوان');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'card_is_empty', 'البطاقه خاليه');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_order_has_been_placed_successfully', 'تم وضع طلبك بنجاح');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'purchased', 'تم شراؤها');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_purchased_found', 'لم يتم العثور على عناصر تم شراؤها');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order', 'طلب');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'download_invoice', 'تحميل فاتورة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'id_can_not_empty', 'معرف مطلوب');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_are_not_purchased', 'أنت لم تشتري بعد.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order_not_found', 'غير موجود');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order_details', 'تفاصيل الطلب');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'write_review', 'أكتب مراجعة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'request_refund', 'طلب استرداد');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_details', 'تتبع التفاصيل');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delivery_address', 'عنوان التسليم');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'if_the_order_status', 'إذا لم يتم تعيين حالة الطلب لتسليمها في غضون 60 يوما من تاريخ الطلب، فسيتم إرسالها تلقائيا إلى "تسليمها".');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'if_the_order_delivered', 'إذا لم يتم تسليم الطلب بالفعل، فيمكن للمشتري طلب استرداد.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'placed', 'وضع');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payments', 'المدفوعات');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'subtotal', 'فرعي');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sale_invoice', 'الفاتورة بيع');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seller_name', 'البائع اسم');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seller_email', 'البريد الإلكتروني للبائع');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invoice_to', 'فاتورة إلى');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_details', 'بيانات الدفع');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_due', 'الاجمالي المستحق');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'bank_name', 'اسم البنك');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invoice', 'فاتورة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'item', 'العنصر');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'orders', 'الطلب #٪ s');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_orders_found', 'لم يتم العثور على أية طلبات');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'products', 'منتجات');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'qty', 'الكمية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'canceled', 'ألغيت');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'accepted', 'قبلت');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'packed', 'معباه');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'shipped', 'شحنها');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'commission', 'لجنة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'final_price', 'السعر النهائي');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_number', 'أرقام التتبع');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'link', 'وصلة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_info_has_been_saved_successfully', 'تم حفظ معلومات التتبع بنجاح');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_url_can_not_be_empty', 'لا يمكن أن يكون تتبع عنوان URL فارغا');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_number_can_not_be_empty', 'عدد تتبع لا يمكن أن يكون فارغا');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_enter_valid_url', 'أدخل رابط صحيح من فضلك');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'site_url', 'URL الموقع');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delivered', 'تم التوصيل');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_explain_the_reason', 'يرجى توضيح السبب');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_request_is_under_review', 'طلبك قيد المراجعة، نتواصل معك مرة واحدة.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review', 'إعادة النظر');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'submit', 'إرسال');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review_can_not_be_empty', 'محتوى المراجعة مطلوب.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'rating_can_not_be_empty', 'التصنيف لا يمكن أن يكون فارغا');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review_has_been_sent_successfully', 'تم تقديم مراجعتك.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'admin_status_changed', 'تم تغيير حالة الطلب');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'new_orders_has_been_placed', 'وقد وضعت أوامر جديدة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'added_tracking_info', 'وأضاف معلومات التتبع');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'product_approved', 'تمت الموافقة على منتجك');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_product_is_under_review', 'المنتج الخاص بك قيد المراجعة حاليا.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tweet', 'سقسقة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask', 'طلب');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'write_answer', 'اكتب إجابة واضغط على Enter');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'reply_to_answer', 'الرد على الإجابة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'answered_your_question', 'أجاب سؤالك');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'replied_to_answer', 'أجاب عن إجابتك');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_question', 'أعجبك سؤالك');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_answer', 'أحببت إجابتك');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'answer_mention', 'ذكرت لك على إجابة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'question_mention', 'ذكرت لك على سؤال');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'verified_purchase', 'التحقق من الشراء');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_reviews_found', 'لم يتم العثور على مراجعات');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask_anonymously', 'اسأل متخفيا');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask_friend', 'أسأل صديق');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_for_friends', 'البحث عن أصدقاء');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'askfm_box_placeholder', 'ماذا، متى، لماذا ... اسأل');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'question_can_not_empty', 'السؤال مطلوب.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_who_you_want_ask', 'يرجى تحديد من تريد أن تسأل');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'asked_you_a_question', 'طلب منك سؤال');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'trending_questions', 'أسئلة تتجه');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'people_liked_question', 'الناس يحبون هذا السؤال');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'users_liked_answer', 'الناس يحبون هذه الإجابة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_answers_found', 'لا إجابات لإظهار');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_header_people', 'البحث عن الناس و #hashtags');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'questions', 'أسئلة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tweets', 'التغريدات');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'trending_tweets', 'تتجه تغريدات');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'people_liked_tweet', 'الناس يحبون هذه التغريدة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_tweet', 'أحب تغريدك');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_a_file_to_upload', 'يرجى تحديد ملف لتحميل');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'unlock_content_post_text', 'فتح هذا المحتوى من خلال أن تصبح راعي');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'join_now', 'نضم الان');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patreon_membership_price', 'باتريون عضوية السعر');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience', 'خبرة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_experience', 'إضافة تجربة جديدة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'company_name', 'اسم الشركة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'employment_type', 'نوع الوظيفة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'full_time', 'وقت كامل');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'part_time', 'دوام جزئى');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'self_employed', 'العاملون لحسابهم الخاص');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'freelance', 'حسابهم الخاص');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'contract', 'اتفافية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'internship', 'التدريب الداخلي');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'apprenticeship', 'فترة التدريب في المهنة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seasonal', 'موسمي');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'industry', 'صناعة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'title_empty', 'الرجاء إدخال عنوان');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'company_name_empty', 'الرجاء إدخال اسم الشركة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'employment_type_empty', 'الرجاء إدخال نوع العمالة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'location_empty', 'الرجاء إدخال الموقع');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'start_date_empty', 'الرجاء إدخال تاريخ البدء');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'industry_empty', 'الرجاء إدخال صناعة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'description_empty', 'الرجاء إدخال وصف');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_choose_correct_experience_date', 'يرجى اختيار تاريخ الصحيح.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience_successfully_created', 'تجربة تم إنشاؤها بنجاح.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'valid_link', 'الرجاء إدخال رابط صالح');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_experience', 'حذف تجربتك');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_experience', 'هل أنت متأكد أنك تريد حذف هذه التجربة؟');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_experience', 'تحرير الخبرة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_not_owner', 'أنت لست المالك، يمكنك تطبيق هذا الإجراء.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience_successfully_updated', 'تجربة تم تحديثها بنجاح.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certifications', 'الشهادات');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'licenses_certifications', 'التراخيص والشهادات');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_certification', 'إضافة شهادة جديدة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issuing_organization', 'مؤسسة إصدار');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'credential_id', 'معرف الاعتماد');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'credential_url', 'عنوان الاعتماد URL.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issuing_organization_empty', 'الرجاء إدخال مؤسسة إصدار');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issue_date', 'تاريخ الإصدار');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'expiration_date', 'تاريخ انتهاء الصلاحية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issue_date_empty', 'الرجاء إدخال تاريخ الإصدار.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'name_empty', 'الرجاء إدخال اسم');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_successfully_created', 'تم إنشاء شهادتك.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_certification', 'حذف شهاداتك');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_certification', 'هل أنت متأكد أنك تريد حذف هذه الشهادة؟');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_certification', 'تحرير الشهادة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_successfully_updated', 'تم تحديث شهادتك.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'projects', 'مشاريع');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_project', 'إضافة مشروع جديد');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_name', 'اسم المشروع');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'associated_with', 'مرتبط ب');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_url', 'عنوان المشروع');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_successfully_added', 'تمت إضافة مشروعك.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_project', 'حذف مشروعك');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_project', 'هل أنت متأكد أنك تريد حذف هذا المشروع؟');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_project', 'تحرير المشروع');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_successfully_updated', 'تم تحديث مشروعك.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'skills', 'مهارات');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'languages', 'اللغات');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to', 'مفتوح ل');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'finding_a_job', 'العثور على وظيفة جديدة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'providing_services', 'تقديم خدمات');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'hiring', 'توظيف');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_job_preferences', 'إضافة تفضيلات الوظيفة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tell_us_kind_work', 'أخبرنا أي نوع من العمل المفتوح');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'workplaces', 'أماكن العمل');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_title', 'عناوين العمل');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'on_site', 'بالموقع');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'hybrid', 'هجين');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remote', 'بعيد');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_types', 'أنواع الوظائف');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'temporary', 'مؤقت');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_location', 'مكان العمل');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Job_title_empty', 'المسمى الوظيفي لا يمكن أن يكون فارغا');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_location_empty', 'موقع الوظيفة لا يمكن أن يكون فارغا');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'workplaces_empty', 'يرجى اختيار مكان العمل');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_type_empty', 'يرجى اختيار نوع الوظيفة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences_saved_successfully', 'تم تحديث تفضيلات الوظيفة.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to_work', 'مفتوحة للعمل');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'see_all_details', 'انظر كل التفاصيل');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences', 'تفضيلات الوظيفة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'set_up_services_page', 'دعونا إعداد صفحة الخدمات الخاصة بك');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services', 'خدمات');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_empty', 'الخدمات لا يمكن أن تكون فارغة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_saved_successfully', 'تم حفظ الخدمات.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_provided', 'تقديم خدمات');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invalid_id', 'بطاقة التعريف غير صالحة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_edited_successfully', 'تم تحديث الخدمات.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_job_preferences', 'تحرير تفضيلات الوظيفة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences_edited_successfully', 'تم تحرير تفضيلات الوظيفة.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_services', 'تحميل المزيد من الخدمات');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tiers', 'tiers.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_offer_patrons', 'اختر ما يقدمه رعاةك');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_tier', 'إضافة المستوى');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_title', 'عنوان المستوى');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_price', 'السعر المستوى');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_image', 'صورة المستوى');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_description', 'وصف الطبقة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'benefits', 'فوائد');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_without_audio_video', 'دردشة بدون مكالمة صوتية وفيديو');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_with_audio_without_video', 'الدردشة مع مكالمة صوتية ودون مكالمة فيديو');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_without_audio_with_video', 'الدردشة دون مكالمة صوتية ومع مكالمة الفيديو');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_with_audio_video', 'الدردشة مع مكالمة الصوت والفيديو');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat', 'محادثة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'live_stream', 'البث المباشر');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'price_empty', 'السعر لا يمكن أن يكون فارغا');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'benefits_empty', 'الفوائد لا يمكن أن تكون فارغة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_chat_type', 'يرجى اختيار نوع الدردشة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_added_successfully', 'تمت إضافة المستوى بنجاح');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_tier', 'تحرير المستوى');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_updated_successfully', 'tier تم تحديثها بنجاح');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_tier', 'حذف المستوى الخاص بك');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_tier', 'هل أنت متأكد من أنك تريد حذف هذا المستوى؟');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patron', 'كفيل');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patrons', 'رعاة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_you_may_know', 'الخدمات التي قد ترغب');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to_work_posts', 'مفتوحة لمشاركات العمل');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Afrikaans_af', 'الأفريكان');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Albanian_sq', 'الألبانية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Amharic_am', 'الأمهرية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Arabic_ar', 'عربى');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Aragonese_an', 'aragonese.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Armenian_hy', 'الأرمن');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Asturian_ast', 'الأسترية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Azerbaijani_az', 'أذربيجاني');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Basque_eu', 'الباسك');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Belarusian_be', 'البيلاروسية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bengali_bn', 'البنغالية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bosnian_bs', 'البوسنة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Breton_br', 'بريتون');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bulgarian_bg', 'البلغارية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Catalan_ca', 'الكاتالونية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Central Kurdish_ckb', 'كردي المركزي');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Chinese_zh', 'صينى');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Corsican_co', 'كورسيكان');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Croatian_hr', 'الكرواتية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Czech_cs', 'جمهورية التشيك');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Danish_da', 'دانماركي');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Dutch_nl', 'هولندي');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'English_en', 'إنجليزي');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Esperanto_eo', 'الإسبرانتو');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Estonian_et', 'إستونيا');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Faroese_fo', 'الفارويز');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Filipino_fil', 'الفلبينية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Finnish_fi', 'الفنلندية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'French_fr', 'فرنسي');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Galician_gl', 'الجاليكية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Georgian_ka', 'جورجيا');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'German_de', 'ألمانية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Greek_el', 'اليونانية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Guarani_gn', 'غواراني');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Gujarati_gu', 'الغوجاراتاتي');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hausa_ha', 'هوسا');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hawaiian_haw', 'هاواي');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hebrew_he', 'اللغة العبرية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hindi_hi', 'اللغة الهندية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hungarian_hu', 'المجر');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Icelandic_is', 'الأيسلاندية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Indonesian_id', 'إندونيسي');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Interlingua_ia', 'Interlingua.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Irish_ga', 'الأيرلندية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Italian_it', 'الإيطالية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Japanese_ja', 'اليابانية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kannada_kn', 'كانادا');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kazakh_kk', 'كازاخستان');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Khmer_km', 'الخمير');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Korean_ko', 'الكورية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kurdish_ku', 'كردي');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kyrgyz_ky', 'قيرغيزستان');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lao_lo', 'لاو');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Latin_la', 'اللاتينية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Latvian_lv', 'اللاتفية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lingala_ln', 'لينغالا');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lithuanian_lt', 'اللتوانية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Macedonian_mk', 'مقدونيان');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Malay_ms', 'لغة الملايو');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Malayalam_ml', 'المالايالام');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Maltese_mt', 'المالطية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Marathi_mr', 'ماراثي');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Mongolian_mn', 'المنغولية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Nepali_ne', 'النيبالي');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian_no', 'النرويجية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian Bokmål_nb', 'bokmål النرويجية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian Nynorsk_nn', 'Norwegian Nynorsk.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Occitan_oc', 'Occitan.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Oriya_or', 'oriya.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Oromo_om', 'oromo.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Pashto_ps', 'البشتونية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Persian_fa', 'اللغة الفارسية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Polish_pl', 'تلميع');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Portuguese_pt', 'البرتغالية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Punjabi_pa', 'بونجابي');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Quechua_qu', 'quechua.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Romanian_ro', 'روماني');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Romansh_rm', 'الرومانش');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Russian_ru', 'الروسية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Scottish Gaelic_gd', 'الغيلية الاسكتلندية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Serbian_sr', 'الصربية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Serbo_sh', 'صرف');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Shona_sn', 'شونا');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sindhi_sd', 'السندهي');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sinhala_si', 'السنهالية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Slovak_sk', 'سلوفاكي');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Slovenian_sl', 'السلوفينية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Somali_so', 'الصومالي');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Southern Sotho_st', 'جنوب سوثو');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Spanish_es', 'الأسبانية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sundanese_su', 'Sundanese.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Swahili_sw', 'السواحيلية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Swedish_sv', 'السويدية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tajik_tg', 'طاجيك');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tamil_ta', 'التاميل');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tatar_tt', 'التتار');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Telugu_te', 'التيلجو');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Thai_th', 'التايلاندية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tigrinya_ti', 'تيغرينيا');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tongan_to', 'تونجي');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Turkish_tr', 'اللغة التركية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Turkmen_tk', 'التركمان');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Twi_tw', 'TWI.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Ukrainian_uk', 'الأوكرانية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Urdu_ur', 'الأردية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Uyghur_ug', 'uyghur.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Uzbek_uz', 'الأوزبكية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Vietnamese_vi', 'الفيتنامية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Walloon_wa', 'والون');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Welsh_cy', 'تهرب من دفع الرهان');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Western Frisian_fy', 'الغربية فريسيان');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Xhosa_xh', 'Xhosa.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Yiddish_yi', 'اليديشية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Yoruba_yo', 'يوروبا');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Zulu_zu', 'الزولو');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_available_data', 'لا توجد بيانات متاحة لإظهارها.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'my_wallet_', 'محفظتي');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_have_bought_products', 'لقد اشتريت منتج');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sale_products', 'بيع المنتجات');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'entire_site', 'الموقع بأكمله');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'banned_for_violating', 'لقد حظرت لانتهاك شروط استخدامنا. ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'were_banned_from', 'عفوا، تم حظرك من {site_name}');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'contact_us_more_details', 'يرجى الاتصال بنا للحصول على مزيد من التفاصيل.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pdf_file', 'إرفاق ملف pdf');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_file', 'شهادة');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_currently_working', 'هل تعمل حاليا؟');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'am_looking_to_work', 'لا أنا أتطلع إلى العمل');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'am_looking_for_employees', 'نعم أنا أبحث عن الموظفين');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'products_for_sale', 'منتجات للبيع');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_notifications_because_you_were_banned', 'لا يمكنك عرض الإخطارات الخاصة بك لأنك تم حظرك');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_messages_because_you_were_banned', 'لا يمكنك عرض رسائلك لأنك تم حظرك');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_requests_because_you_were_banned', 'لا يمكنك عرض طلباتك لأنك تم حظرك');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'withdrawal', 'انسحاب');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'successfully_received_from', 'تم استلام الأموال بنجاح من');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'terms_of_use_page', '<H4> 1- اكتب شروط الاستخدام الخاصة بك هنا. </ H4>
      ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'privacy_policy_page', '');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'about_page', '<h4> 1- اكتب عن موقع الويب الخاص بك هنا. </ h4> Lorem Ipsum Dolor Sit Amet، Consectedur ALIT ELIT، SED Do Eiusmod Incididund Incididunt UT Labore Et Dolore Magna Aliqua. ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_terms_page', '<H4> 1- اكتب شروط الاستخدام الخاصة بك هنا. </ H4>
      ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'added_review_to_your_product', 'استعرض المنتج الخاص بك');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'coinbase', 'coinbase.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'product_purchase', 'شراء المنتج');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sold_a_product', 'بيع المنتج');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'describe_your_review', 'صف رأيك هنا ..');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'related_prods', 'منتجات ذات صله');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_open_work', 'هل أنت متأكد أنك تريد حذف؟');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_services', 'هل أنت متأكد أنك تريد حذف هذه الخدمات؟');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_find_job_at', 'البحث، البحث والتقدم بطلب للحصول على فرص العمل في');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'connect_with_friends', 'تواصل مع الأصدقاء!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'login_connect_friends', 'تسجيل الدخول إلى حساب {site_name} والاتصال بأصدقائك!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remember_device', 'تذكر هذا الجهاز');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'register_create_account', 'قم بإنشاء حساب {site_name}!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_upgrade_to_upload', 'فقط المستخدمين للمحترفين يمكنهم تحميل الرجاء الترقية إلى الموالية');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'type_something_to_post', 'لا يمكن أن يكون محتوى المنشور فارغا.');
        } else if ($value == 'dutch') {
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_must_add_text_or_image_first', 'U moet een tekst of afbeelding toevoegen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_to_cart', 'Voeg toe aan winkelmandje');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remove_from_cart', 'Verwijderen van winkelwagen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_from_wallet', 'Betaal per portemonnee');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_top_up_wallet', 'Je hebt niet genoeg balans om te kopen, vul je portemonnee bij.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_to_upgrade', 'U staat op het punt om een ​​pro-lid te upgraden.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_to_fund', 'Je staat op het punt te doneren.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'amount_can_not_empty', 'Bedrag is vereist');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'fund_not_found', 'Financiering wordt niet gevonden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_successfully_done', 'Betaling succesvol gedaan, dank u!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'buy_now', 'Koop nu');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_item', 'Totale itemeenheden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_item_not_empty', 'Artikeleenheden zijn vereist');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'currently_unavailable', 'Momenteel niet beschikbaar.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'checkout', 'Uitchecken');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_items_found', 'Geen items gevonden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total', 'Totaal');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'my_addresses', 'Mijn adressen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new', 'Voeg nieuw toe');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_address', 'Nieuw adres toevoegen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_added', 'Uw adres is succesvol toegevoegd');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_address', 'Verwijder uw adres');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_address', 'Weet je zeker dat je dit adres wilt verwijderen?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_address', 'verander adres');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_edited', 'Uw adres is succesvol bewerkt');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_add_address', 'Voeg alstublieft een nieuw adres toe');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_address', 'Selecteer een adres');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_alert', 'Betalingswaarschuwing');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'purchase_the_items', 'Je staat op het punt om de items te kopen, wil je doorgaan?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'shopping_cart', 'Winkelwagen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'items', 'Items');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'back_to_shop', 'Terug naar winkel');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'some_products_units', 'Sommige producten zijn niet op voorraad.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_can_not_be_empty', 'Adres kan niet leeg zijn');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_not_found', 'Adres niet gevonden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'card_is_empty', 'Winkelwagen is leeg');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_order_has_been_placed_successfully', 'Uw bestelling is succesvol geplaatst');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'purchased', 'Gekocht');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_purchased_found', 'Geen aangekochte items gevonden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order', 'Volgorde');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'download_invoice', 'Factuur downloaden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'id_can_not_empty', 'ID is vereist');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_are_not_purchased', 'Je hebt nog niet gekocht.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order_not_found', 'Bestellen niet gevonden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order_details', 'Bestel Details');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'write_review', 'Schrijf recensie');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'request_refund', 'Vraag een terugbetaling');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_details', 'Tracking details');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delivery_address', 'Bezorgadres');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'if_the_order_status', 'Als de bestelstatus niet is ingesteld op afgeleverd binnen 60 dagen na de besteldatum, wordt deze automatisch verzonden naar "geleverd".');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'if_the_order_delivered', 'Als de bestelling niet daadwerkelijk is afgeleverd, kan de koper een terugbetaling aanvragen.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'placed', 'Geplaatst');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payments', 'Betalingen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'subtotal', 'Subtotaal');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sale_invoice', 'Verkoopfactuur');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seller_name', 'Naam van de verkoper');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seller_email', 'Verkoper e-mail');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invoice_to', 'Factuur aan');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_details', 'Betalingsdetails');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_due', 'Totaal verschuldigd');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'bank_name', 'banknaam');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invoice', 'Factuur');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'item', 'Item');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'orders', 'Bestellingen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_orders_found', 'Geen bestellingen gevonden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'products', 'Producten');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'qty', 'Titel');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'canceled', 'Geannuleerd');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'accepted', 'Geaccepteerd');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'packed', 'Ingepakt');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'shipped', 'Verzenden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'commission', 'Commissie');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'final_price', 'Uiteindelijke prijs');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_number', 'Volg nummer');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'link', 'Koppeling');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_info_has_been_saved_successfully', 'Tracking-informatie is succesvol opgeslagen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_url_can_not_be_empty', 'Tracking-URL kan niet leeg zijn');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_number_can_not_be_empty', 'Tracking-nummer kan niet leeg zijn');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_enter_valid_url', 'Voer een geldige URL in');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'site_url', 'Site URL');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delivered', 'Afgeleverd');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_explain_the_reason', 'Leg de reden uit');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_request_is_under_review', 'Uw verzoek wordt beoordeeld, we nemen eenmaal contact op met u.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review', 'Beoordeling');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'submit', 'Indienen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review_can_not_be_empty', 'Review Content is vereist.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'rating_can_not_be_empty', 'beoordeling kan niet leeg zijn');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review_has_been_sent_successfully', 'Uw beoordeling is ingediend.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'admin_status_changed', 'Bestelstatus is gewijzigd');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'new_orders_has_been_placed', 'Nieuwe bestellingen zijn geplaatst');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'added_tracking_info', 'Tracking-info toegevoegd');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'product_approved', 'Uw product is goedgekeurd');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_product_is_under_review', 'Uw product wordt momenteel beoordeeld.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tweet', 'Tweet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask', 'Vragen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'write_answer', 'Schrijf een antwoord en druk op ENTER');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'reply_to_answer', 'Antwoord om te antwoorden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'answered_your_question', 'beantwoordde je vraag');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'replied_to_answer', 'antwoordde op je antwoord');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_question', 'vond je vraag leuk');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_answer', 'vond je antwoord leuk');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'answer_mention', 'Genoemde je op een antwoord');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'question_mention', 'Ik heb je op een vraag genoemd');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'verified_purchase', 'Geverifieerde aankoop');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_reviews_found', 'Geen reviews gevonden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask_anonymously', 'vraag anoniem');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask_friend', 'Vraag een vriend');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_for_friends', 'Zoek naar vrienden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'askfm_box_placeholder', 'Wat, wanneer, waarom ... vraag');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'question_can_not_empty', 'Vraag is vereist.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_who_you_want_ask', 'Selecteer alstublieft wie u wilt vragen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'asked_you_a_question', 'vroeg je een vraag');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'trending_questions', 'Vragen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'people_liked_question', 'Mensen vonden deze vraag leuk');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'users_liked_answer', 'Mensen vonden dit antwoord leuk');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_answers_found', 'Geen antwoorden om te laten zien');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_header_people', 'Zoek naar mensen en #hashtags');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'questions', 'Vragen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tweets', 'Tweets');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'trending_tweets', 'Trending tweets');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'people_liked_tweet', 'Mensen vonden deze tweet leuk');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_tweet', 'vond je tweet leuk');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_a_file_to_upload', 'Selecteer een bestand om te uploaden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'unlock_content_post_text', 'Ontgrendel deze inhoud door een patroon te worden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'join_now', 'Lid worden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patreon_membership_price', 'Patrum-lidmaatschap Prijs');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience', 'Ervaring');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_experience', 'Voeg nieuwe ervaring toe');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'company_name', 'Bedrijfsnaam');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'employment_type', 'Type werkgelegenheid');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'full_time', 'Full time');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'part_time', 'Deeltijd');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'self_employed', 'Eigen baas');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'freelance', 'Freelance');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'contract', 'Contract');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'internship', 'Stage');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'apprenticeship', 'Stage');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seasonal', 'Seizoensgebonden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'industry', 'Industrie');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'title_empty', 'Voer een titel in');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'company_name_empty', 'Voer een bedrijfsnaam in');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'employment_type_empty', 'Voer een werkgelegenheidstype in');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'location_empty', 'Voer een locatie in');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'start_date_empty', 'Voer een startdatum in');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'industry_empty', 'Voer een industrie in');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'description_empty', 'Voer een beschrijving in');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_choose_correct_experience_date', 'Kies een juiste datum.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience_successfully_created', 'Ervaring met succes gemaakt.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'valid_link', 'Voer een geldige link in');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_experience', 'Verwijder je ervaring');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_experience', 'Weet je zeker dat je deze ervaring wilt verwijderen?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_experience', 'Bewerk ervaring');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_not_owner', 'U bent niet de eigenaar, u kunt deze actie toepassen.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience_successfully_updated', 'Ervaring met succes bijgewerkt.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certifications', 'Certificeringen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'licenses_certifications', 'Licenties en certificaten');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_certification', 'Voeg nieuw certificaat toe');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issuing_organization', 'Uitgevende organisatie');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'credential_id', 'Referent-ID');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'credential_url', 'Referent-url');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issuing_organization_empty', 'Voer een uitgevende organisatie in');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issue_date', 'Datum van publicatie');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'expiration_date', 'Uiterste houdbaarheidsdatum');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issue_date_empty', 'Voer de uitgifte-datum in.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'name_empty', 'Voer een naam in');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_successfully_created', 'Uw certificaat is gemaakt.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_certification', 'Verwijder uw certificaat');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_certification', 'Weet u zeker dat u dit certificaat wilt verwijderen?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_certification', 'Bewerk certificaat');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_successfully_updated', 'Uw certificaat is bijgewerkt.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'projects', 'Projecten');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_project', 'Voeg nieuw project toe');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_name', 'Naam van het project');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'associated_with', 'Geassocieerd met');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_url', 'Project URL');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_successfully_added', 'Uw project is toegevoegd.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_project', 'Verwijder uw project');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_project', 'Weet je zeker dat je dit project wilt verwijderen?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_project', 'Project bewerken');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_successfully_updated', 'Uw project is bijgewerkt.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'skills', 'Vaardigheden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'languages', 'Talen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to', 'Open voor');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'finding_a_job', 'Een nieuwe baan vinden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'providing_services', 'Het verlenen van diensten');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'hiring', 'In dienst nemen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_job_preferences', 'Voeg taakvoorkeuren toe');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tell_us_kind_work', 'Vertel ons wat voor soort werk je open bent');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'workplaces', 'Werkplekken');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_title', 'Titels');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'on_site', 'Ter plekke');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'hybrid', 'Hybride');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remote', 'Op afstand');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_types', 'Typen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'temporary', 'Tijdelijk');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_location', 'Werklocatie');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Job_title_empty', 'Functie kan niet leeg zijn');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_location_empty', 'Werklocatie kan niet leeg zijn');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'workplaces_empty', 'Selecteer een werkplek');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_type_empty', 'Selecteer een taaktype');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences_saved_successfully', 'Jobvoorkeuren zijn bijgewerkt.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to_work', 'Open voor het werk');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'see_all_details', 'Zie alle details');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences', 'Baan voorkeur');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'set_up_services_page', 'Laten we uw servicepagina instellen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services', 'Diensten');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_empty', 'Services kunnen niet leeg zijn');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_saved_successfully', 'Services zijn opgeslagen.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_provided', 'Aangeboden diensten');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invalid_id', 'Ongeldig identiteitsbewijs');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_edited_successfully', 'Diensten zijn bijgewerkt.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_job_preferences', 'Bewerk jobvoorkeuren');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences_edited_successfully', 'Werkvoorkeuren zijn bewerkt.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_services', 'Laad meer diensten');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tiers', 'Tijper');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_offer_patrons', 'Kies wat je mecenassen aanbiedt');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_tier', 'Voeg tier toe');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_title', 'Titel titel');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_price', 'Tierprijs');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_image', 'Tier afbeelding');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_description', 'Tierbeschrijving');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'benefits', 'Voordelen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_without_audio_video', 'Chat zonder audio- en videogesprek');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_with_audio_without_video', 'Chatten met audio-oproep en zonder videogesprek');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_without_audio_with_video', 'Chatten zonder audio-oproep en met videogesprek');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_with_audio_video', 'Chat met audio- en videogesprek');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat', 'Chatten');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'live_stream', 'Livestream');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'price_empty', 'Prijs kan niet leeg zijn');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'benefits_empty', 'Voordelen kunnen niet leeg zijn');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_chat_type', 'Selecteer het chattype');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_added_successfully', 'Tier is succesvol toegevoegd');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_tier', 'Bewerk tier');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_updated_successfully', 'Tier succesvol bijgewerkt');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_tier', 'Verwijder je tier');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_tier', 'Weet je zeker dat je deze tier wilt verwijderen?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patron', 'Patroon');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patrons', 'Patronen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_you_may_know', 'Diensten die je misschien leuk vindt');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to_work_posts', 'Open voor werkpalen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Afrikaans_af', 'Afrikaans');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Albanian_sq', 'Albanees');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Amharic_am', 'Amharisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Arabic_ar', 'Arabisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Aragonese_an', 'Aragonese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Armenian_hy', 'Armeens');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Asturian_ast', 'Asturisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Azerbaijani_az', 'Azerbeidzjani');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Basque_eu', 'baskisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Belarusian_be', 'Wit-Russisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bengali_bn', 'Bengaals');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bosnian_bs', 'Bosnisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Breton_br', 'Breton');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bulgarian_bg', 'Bulgaars');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Catalan_ca', 'Catalaans');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Central Kurdish_ckb', 'Centraal koerdisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Chinese_zh', 'Chinese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Corsican_co', 'Corsicaans');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Croatian_hr', 'Kroatisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Czech_cs', 'Tsjechisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Danish_da', 'Deens');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Dutch_nl', 'Nederlands');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'English_en', 'Engels');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Esperanto_eo', 'Esperanto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Estonian_et', 'Estlands');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Faroese_fo', 'Farroom');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Filipino_fil', 'Filipijns');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Finnish_fi', 'Fins');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'French_fr', 'Frans');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Galician_gl', 'Galicisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Georgian_ka', 'Georgisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'German_de', 'Duits');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Greek_el', 'Grieks');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Guarani_gn', 'Guarani');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Gujarati_gu', 'Gujarati');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hausa_ha', 'Hausa');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hawaiian_haw', 'Hawaiiaans');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hebrew_he', 'Hebreeuws');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hindi_hi', 'Hindi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hungarian_hu', 'Hongaars');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Icelandic_is', 'IJslands');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Indonesian_id', 'Indonesisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Interlingua_ia', 'Interlingua');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Irish_ga', 'Iers');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Italian_it', 'Italiaans');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Japanese_ja', 'Japans');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kannada_kn', 'Kannada');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kazakh_kk', 'Kazachs');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Khmer_km', 'Khmer');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Korean_ko', 'Koreaans');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kurdish_ku', 'Koerdisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kyrgyz_ky', 'Kirgizië');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lao_lo', 'Lao');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Latin_la', 'Latijns');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Latvian_lv', 'Letland');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lingala_ln', 'Lingala');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lithuanian_lt', 'Litouws');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Macedonian_mk', 'Macedonisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Malay_ms', 'Maleis-');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Malayalam_ml', 'Malayalam');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Maltese_mt', 'Maltees');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Marathi_mr', 'Marathi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Mongolian_mn', 'Mongools');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Nepali_ne', 'Nepali');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian_no', 'Noors');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian Bokmål_nb', 'Norwegian Bokmål');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian Nynorsk_nn', 'Noors Nynorsk');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Occitan_oc', 'Occitaan');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Oriya_or', 'ORIYA');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Oromo_om', 'Oromo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Pashto_ps', 'Pashto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Persian_fa', 'Perzisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Polish_pl', 'Pools');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Portuguese_pt', 'Portugees');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Punjabi_pa', 'Punjabi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Quechua_qu', 'Quechua');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Romanian_ro', 'Roemeense');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Romansh_rm', 'Romansh');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Russian_ru', 'Russisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Scottish Gaelic_gd', 'Schotse Gaelic');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Serbian_sr', 'Servisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Serbo_sh', 'Serbo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Shona_sn', 'Shona');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sindhi_sd', 'Sindhi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sinhala_si', 'Sinhala');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Slovak_sk', 'Slowaaks');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Slovenian_sl', 'Sloveens');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Somali_so', 'Somalisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Southern Sotho_st', 'Zuidelijke sotho');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Spanish_es', 'Spaans');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sundanese_su', 'Sundanese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Swahili_sw', 'Swahili');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Swedish_sv', 'Zweeds');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tajik_tg', 'Tadjik');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tamil_ta', 'Tamil');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tatar_tt', 'Tatar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Telugu_te', 'Telugu');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Thai_th', 'Thais');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tigrinya_ti', 'Tigrinya');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tongan_to', 'Tongan');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Turkish_tr', 'Turks');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Turkmen_tk', 'Turkmen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Twi_tw', 'Twi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Ukrainian_uk', 'Oekraïens');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Urdu_ur', 'Urdu');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Uyghur_ug', 'Uyghur');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Uzbek_uz', 'Oezbeek');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Vietnamese_vi', 'Vietnamees');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Walloon_wa', 'Waals');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Welsh_cy', 'Welsh');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Western Frisian_fy', 'Westerse Fries');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Xhosa_xh', 'Xhosa');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Yiddish_yi', 'Jiddisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Yoruba_yo', 'Yoruba');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Zulu_zu', 'Zulu');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_available_data', 'Geen beschikbare gegevens om te laten zien.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'my_wallet_', 'Mijn portemonnee');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_have_bought_products', 'Je hebt een product gekocht');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sale_products', 'Verkoop Producten');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'entire_site', 'Gehele site');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'banned_for_violating', 'Je was verbannen voor het schenden van onze gebruiksvoorwaarden. ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'were_banned_from', 'Oeps, je bent verbannen van {site_name}');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'contact_us_more_details', 'Gelieve {CONTACT_US} voor meer informatie.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pdf_file', 'Bevestig PDF-bestand');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_file', 'Certificaat');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_currently_working', 'Werk je momenteel?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'am_looking_to_work', 'Nee, ik ben op zoek om te werken');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'am_looking_for_employees', 'Ja, ik ben op zoek naar werknemers');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'products_for_sale', 'Producten te koop');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_notifications_because_you_were_banned', 'Je kunt je meldingen niet bekijken omdat je bent verbannen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_messages_because_you_were_banned', 'Je kunt je berichten niet bekijken omdat je bent verbannen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_requests_because_you_were_banned', 'Je kunt je verzoeken niet bekijken omdat je bent verbannen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'withdrawal', 'Opname');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'successfully_received_from', 'Geld is succesvol ontvangen van');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'terms_of_use_page', '<H4> 1 - Schrijf hier uw gebruiksvoorwaarden. </ H4>
      ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'privacy_policy_page', '');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'about_page', '<H4> 1 1 1 Schrijf over uw website hier. </ H4> Lorem Ipsum Dolor Sit amet, CONTECTOTURE ADIPISICING ELIT, SED DO EIANMOD TRORT INCIDIDUNT UT LABORE et Dolore Magna Aliqua. ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_terms_page', '<H4> 1 - Schrijf hier uw gebruiksvoorwaarden. </ H4>
      ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'added_review_to_your_product', 'Beoordeeld op uw product');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'coinbase', 'Coinbase');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'product_purchase', 'Productaankoop');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sold_a_product', 'Productverkoop');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'describe_your_review', 'Beschrijf hier uw beoordeling ..');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'related_prods', 'gerelateerde producten');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_open_work', 'Weet je zeker dat je wilt verwijderen?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_services', 'Weet je zeker dat je deze services wilt verwijderen?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_find_job_at', 'Zoeken, vinden en toepassen op vacatures bij');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'connect_with_friends', 'Maak verbinding met vrienden!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'login_connect_friends', 'Log in op uw {site_name}-account en maak verbinding met je vrienden!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remember_device', 'Onthoud dit apparaat');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'register_create_account', 'Maak uw {site_name}-account aan!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_upgrade_to_upload', 'Just Pro-gebruikers kunnen uploaden upgrade naar Pro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'type_something_to_post', 'Uw post-inhoud kan niet leeg zijn.');
        } else if ($value == 'french') {
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_must_add_text_or_image_first', 'Vous devez ajouter un texte ou une image');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_to_cart', 'Ajouter au panier');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remove_from_cart', 'Supprimer du panier');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_from_wallet', 'Payer par portefeuille');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_top_up_wallet', 'Vous n\'avez pas assez d\'équilibre pour acheter, veuillez recharger votre portefeuille.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_to_upgrade', 'Vous êtes sur le point de passer à un membre professionnel.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_to_fund', 'Vous êtes sur le point de faire un don.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'amount_can_not_empty', 'Montant est requis');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'fund_not_found', 'Le financement n\'est pas trouvé');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_successfully_done', 'Paiement effectué avec succès, merci!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'buy_now', 'Acheter maintenant');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_item', 'Total des unités d\'article');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_item_not_empty', 'Les unités d\'article sont nécessaires');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'currently_unavailable', 'Actuellement indisponible.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'checkout', 'Vérifier');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_items_found', 'Aucun élément trouvé');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total', 'Le total');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'my_addresses', 'Mes adresses');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new', 'Ajouter de nouveau');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_address', 'Ajouter une nouvelle adresse');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_added', 'Votre adresse a été ajoutée avec succès');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_address', 'Supprimer votre adresse');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_address', 'Êtes-vous sûr de vouloir supprimer cette adresse?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_address', 'Modifier l\'adresse');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_edited', 'Votre adresse a été modifiée avec succès');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_add_address', 'S\'il vous plaît ajouter une nouvelle adresse');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_address', 'Veuillez sélectionner une adresse');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_alert', 'Alerte de paiement');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'purchase_the_items', 'Vous êtes sur le point d\'acheter les articles, voulez-vous continuer?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'shopping_cart', 'Panier');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'items', 'Articles');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'back_to_shop', 'Retour au magasin');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'some_products_units', 'Certains produits sont en rupture de stock.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_can_not_be_empty', 'L\'adresse ne peut pas être vide');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_not_found', 'Adresse introuvable');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'card_is_empty', 'Le panier est vide');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_order_has_been_placed_successfully', 'Votre commande a été placée avec succès');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'purchased', 'Acheté');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_purchased_found', 'Aucun article acheté trouvé');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order', 'Commander');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'download_invoice', 'Télécharger la facture');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'id_can_not_empty', 'ID est requis');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_are_not_purchased', 'Vous n\'avez pas encore acheté.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order_not_found', 'Commande introuvable');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order_details', 'Détails de la commande');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'write_review', 'Ecrire une critique');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'request_refund', 'Demande à être remboursé');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_details', 'Détails de suivi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delivery_address', 'Adresse de livraison');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'if_the_order_status', 'Si l\'état de la commande n\'était pas défini sur livré dans les 60 jours à compter de la date de la commande, il sera automatiquement envoyé à \"livré\".');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'if_the_order_delivered', 'Si la commande n\'était pas réellement livrée, l\'acheteur peut demander un remboursement.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'placed', 'Mis');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payments', 'Paiements');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'subtotal', 'Total');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sale_invoice', 'Facture de vente');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seller_name', 'Nom du Vendeur');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seller_email', 'Vendeur e-mail');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invoice_to', 'Facture à');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_details', 'Détails de paiement');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_due', 'Total dû');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'bank_name', 'Nom de banque');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invoice', 'Facture d\'achat');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'item', 'Article');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'orders', 'Ordres');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_orders_found', 'Aucune commande trouvée');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'products', 'Des produits');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'qty', 'Quantité');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'canceled', 'Annulé');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'accepted', 'Accepté');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'packed', 'Emballé');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'shipped', 'Expédié');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'commission', 'Commission');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'final_price', 'Prix ​​final');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_number', 'Numéro de suivi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'link', 'Lien');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_info_has_been_saved_successfully', 'Les informations de suivi ont été sauvegardées avec succès');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_url_can_not_be_empty', 'L\'URL de suivi ne peut pas être vide');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_number_can_not_be_empty', 'Le numéro de suivi ne peut pas être vide');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_enter_valid_url', 'S\'il vous plaît entrer une URL valide');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'site_url', 'URL du site');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delivered', 'Livré');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_explain_the_reason', 'S\'il vous plaît expliquer la raison');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_request_is_under_review', 'Votre demande est à l\'étude, nous vous contacterons une fois terminé.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review', 'Passer en revue');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'submit', 'Soumettre');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review_can_not_be_empty', 'Examiner le contenu est requis.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'rating_can_not_be_empty', 'la note ne peut pas être vide');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review_has_been_sent_successfully', 'Votre avis a été soumis.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'admin_status_changed', 'Le statut de commande a été changé');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'new_orders_has_been_placed', 'Les nouvelles commandes ont été placées');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'added_tracking_info', 'Info de suivi ajouté');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'product_approved', 'Votre produit a été approuvé');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_product_is_under_review', 'Votre produit est actuellement à l\'étude.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tweet', 'Tweet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask', 'Interroger');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'write_answer', 'Écrivez une réponse et appuyez sur Entrée');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'reply_to_answer', 'Répondre à répondre');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'answered_your_question', 'répondu à votre question');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'replied_to_answer', 'répondit à votre réponse');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_question', 'aimé votre question');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_answer', 'aimé votre réponse');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'answer_mention', 'vous a mentionné sur une réponse');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'question_mention', 'vous a mentionné sur une question');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'verified_purchase', 'Achat vérifié');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_reviews_found', 'Aucun commentaire trouvé');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask_anonymously', 'demander anonymement');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask_friend', 'Demander à un ami');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_for_friends', 'Rechercher des amis');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'askfm_box_placeholder', 'Quoi, quand, pourquoi ... demander');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'question_can_not_empty', 'Question est requise.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_who_you_want_ask', 'S\'il vous plaît sélectionner qui vous voulez demander');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'asked_you_a_question', 'vous a posé une question');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'trending_questions', 'Questions de tendance');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'people_liked_question', 'Les gens ont aimé cette question');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'users_liked_answer', 'Les gens ont aimé cette réponse');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_answers_found', 'Aucune réponse à montrer');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_header_people', 'Rechercher des personnes et #hashtags');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'questions', 'Des questions');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tweets', 'Tweet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'trending_tweets', 'Tweets de tendance');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'people_liked_tweet', 'Les gens ont aimé ce tweet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_tweet', 'aimé ton tweet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_a_file_to_upload', 'Veuillez sélectionner un fichier à télécharger');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'unlock_content_post_text', 'Déverrouillez ce contenu en devenant un client');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'join_now', 'Adhérer maintenant');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patreon_membership_price', 'Prix ​​d\'adhésion de Patreon');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience', 'De l\'expérience');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_experience', 'Ajouter une nouvelle expérience');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'company_name', 'Nom de la compagnie');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'employment_type', 'Type d\'emploi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'full_time', 'À temps plein');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'part_time', 'À temps partiel');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'self_employed', 'Travailleur indépendant');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'freelance', 'Free-lance');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'contract', 'Contracter');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'internship', 'Stage');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'apprenticeship', 'Apprentissage');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seasonal', 'Saisonnier');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'industry', 'Industrie');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'title_empty', 'S\'il vous plaît entrer un titre');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'company_name_empty', 'S\'il vous plaît entrer un nom d\'entreprise');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'employment_type_empty', 'S\'il vous plaît entrer un type d\'emploi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'location_empty', 'S\'il vous plaît entrer un emplacement');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'start_date_empty', 'S\'il vous plaît entrer une date de début');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'industry_empty', 'S\'il vous plaît entrer dans une industrie');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'description_empty', 'S\'il vous plaît entrer une description');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_choose_correct_experience_date', 'Veuillez choisir une date correcte.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience_successfully_created', 'Expérience créée avec succès.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'valid_link', 'Veuillez entrer un lien valide');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_experience', 'Supprimer votre expérience');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_experience', 'Êtes-vous sûr de vouloir supprimer cette expérience?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_experience', 'Modifier l\'expérience');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_not_owner', 'Vous n\'êtes pas le propriétaire, vous pouvez appliquer cette action.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience_successfully_updated', 'Expérience mise à jour avec succès.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certifications', 'Certifications');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'licenses_certifications', 'Licences et certificats');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_certification', 'Ajouter un nouveau certificat');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issuing_organization', 'Organisation émettrice');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'credential_id', 'ID de créditif');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'credential_url', 'URL de Credential');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issuing_organization_empty', 'S\'il vous plaît entrer une organisation émettrice');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issue_date', 'Date d\'émission');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'expiration_date', 'Date d\'expiration');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issue_date_empty', 'Veuillez entrer la date d\'émission.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'name_empty', 'S\'il vous plaît entrer un nom');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_successfully_created', 'Votre certificat a été créé.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_certification', 'Supprimer votre certificat');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_certification', 'Êtes-vous sûr de vouloir supprimer ce certificat?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_certification', 'Édition de certificat');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_successfully_updated', 'Votre certificat a été mis à jour.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'projects', 'Projets');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_project', 'Ajouter un nouveau projet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_name', 'Nom du projet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'associated_with', 'Associé à');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_url', 'URL du projet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_successfully_added', 'Votre projet a été ajouté.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_project', 'Supprimer votre projet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_project', 'Êtes-vous sûr de vouloir supprimer ce projet?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_project', 'Modifier le projet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_successfully_updated', 'Votre projet a été mis à jour.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'skills', 'Compétences');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'languages', 'Langues');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to', 'Ouvert à');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'finding_a_job', 'Trouver un nouvel emploi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'providing_services', 'Fournissant des services');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'hiring', 'Embauche');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_job_preferences', 'Ajouter des préférences d\'emploi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tell_us_kind_work', 'Dites-nous quel genre de travail vous êtes ouvert à');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'workplaces', 'Lieux de travail');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_title', 'Titres d\'emploi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'on_site', 'Sur site');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'hybrid', 'Hybride');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remote', 'À distance');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_types', 'Types d\'emplois');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'temporary', 'Temporaire');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_location', 'Lieu de travail');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Job_title_empty', 'Le titre du poste ne peut pas être vide');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_location_empty', 'L\'emplacement du travail ne peut pas être vide');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'workplaces_empty', 'Veuillez sélectionner un lieu de travail');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_type_empty', 'Veuillez sélectionner un type d\'emploi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences_saved_successfully', 'Les préférences d\'emploi ont été mises à jour.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to_work', 'Ouvert au travail');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'see_all_details', 'Voir tous les détails');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences', 'Préférences d\'emploi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'set_up_services_page', 'Configurons votre page Services');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services', 'Prestations de service');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_empty', 'Les services ne peuvent pas être vides');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_saved_successfully', 'Les services ont été enregistrés.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_provided', 'Services fournis');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invalid_id', 'ID invalide');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_edited_successfully', 'Les services ont été mis à jour.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_job_preferences', 'Modifier les préférences d\'emploi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences_edited_successfully', 'Les préférences d\'emploi ont été modifiées.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_services', 'Charger plus de services');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tiers', 'Niveaux');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_offer_patrons', 'Choisissez quoi offrir vos clients');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_tier', 'Ajouter un niveau');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_title', 'Titre de niveau');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_price', 'Prix ​​de palier');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_image', 'Image de niveau');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_description', 'Description de niveau');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'benefits', 'Avantages');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_without_audio_video', 'Chatter sans appel audio et vidéo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_with_audio_without_video', 'Chattez avec appel audio et sans appel vidéo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_without_audio_with_video', 'Chattez sans appel audio et avec appel vidéo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_with_audio_video', 'Discutez avec un appel audio et vidéo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat', 'Discuter');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'live_stream', 'Direct');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'price_empty', 'Le prix ne peut pas être vide');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'benefits_empty', 'Les avantages ne peuvent pas être vides');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_chat_type', 'Veuillez sélectionner le type de discussion');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_added_successfully', 'Niveau ajouté avec succès');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_tier', 'Modifier le niveau');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_updated_successfully', 'Niveau mis à jour avec succès');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_tier', 'Supprimer votre niveau');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_tier', 'Êtes-vous sûr de vouloir supprimer ce niveau?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patron', 'mécène');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patrons', 'Patrons');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_you_may_know', 'Services que vous pouvez aimer');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to_work_posts', 'Ouvert aux postes de travail');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Afrikaans_af', 'afrikaans');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Albanian_sq', 'albanais');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Amharic_am', 'Amharique');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Arabic_ar', 'arabe');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Aragonese_an', 'Aragonais');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Armenian_hy', 'arménien');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Asturian_ast', 'Asturian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Azerbaijani_az', 'azerbaïdjanais');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Basque_eu', 'basque');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Belarusian_be', 'Biélorusse');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bengali_bn', 'bengali');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bosnian_bs', 'Bosniaque');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Breton_br', 'Breton');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bulgarian_bg', 'bulgare');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Catalan_ca', 'catalan');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Central Kurdish_ckb', 'Kurde central');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Chinese_zh', 'chinois');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Corsican_co', 'Corse');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Croatian_hr', 'croate');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Czech_cs', 'tchèque');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Danish_da', 'danois');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Dutch_nl', 'néerlandais');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'English_en', 'Anglais');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Esperanto_eo', 'espéranto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Estonian_et', 'estonien');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Faroese_fo', 'Farsee');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Filipino_fil', 'Philippin');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Finnish_fi', 'finlandais');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'French_fr', 'français');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Galician_gl', 'Galicien');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Georgian_ka', 'géorgien');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'German_de', 'allemand');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Greek_el', 'grec');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Guarani_gn', 'Guarani');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Gujarati_gu', 'Gujarati');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hausa_ha', 'Hausa');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hawaiian_haw', 'hawaïen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hebrew_he', 'hébreu');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hindi_hi', 'hindi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hungarian_hu', 'hongrois');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Icelandic_is', 'islandais');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Indonesian_id', 'indonésien');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Interlingua_ia', 'Interlingua');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Irish_ga', 'irlandais');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Italian_it', 'italien');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Japanese_ja', 'Japonais');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kannada_kn', 'Kannada');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kazakh_kk', 'Kazakh');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Khmer_km', 'Khmer');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Korean_ko', 'coréen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kurdish_ku', 'kurde');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kyrgyz_ky', 'Kirghize');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lao_lo', 'Lao');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Latin_la', 'Latin');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Latvian_lv', 'letton');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lingala_ln', 'Lingala');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lithuanian_lt', 'lituanien');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Macedonian_mk', 'Macédonien');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Malay_ms', 'malais');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Malayalam_ml', 'Malayalam');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Maltese_mt', 'maltais');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Marathi_mr', 'Marathi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Mongolian_mn', 'mongol');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Nepali_ne', 'népalais');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian_no', 'norvégien');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian Bokmål_nb', 'Norvégien bokmål');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian Nynorsk_nn', 'Norvégien Nynorsk');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Occitan_oc', 'Occitan');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Oriya_or', 'Oriya');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Oromo_om', 'Oromo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Pashto_ps', 'Pashto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Persian_fa', 'persan');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Polish_pl', 'polonais');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Portuguese_pt', 'Portugais');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Punjabi_pa', 'Punjabi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Quechua_qu', 'Quechua');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Romanian_ro', 'roumain');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Romansh_rm', 'Romansh');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Russian_ru', 'russe');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Scottish Gaelic_gd', 'Gaélique écossais');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Serbian_sr', 'serbe');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Serbo_sh', 'Serbo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Shona_sn', 'Shona');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sindhi_sd', 'Sindhi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sinhala_si', 'Sinhala');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Slovak_sk', 'slovaque');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Slovenian_sl', 'slovène');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Somali_so', 'somali');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Southern Sotho_st', 'Sotho du sud');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Spanish_es', 'Espagnol');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sundanese_su', 'Sundanais');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Swahili_sw', 'Swahili');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Swedish_sv', 'suédois');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tajik_tg', 'Tajik');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tamil_ta', 'Tamil');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tatar_tt', 'tatar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Telugu_te', 'Telugu');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Thai_th', 'thaïlandais');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tigrinya_ti', 'Tigrinya');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tongan_to', 'Tongan');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Turkish_tr', 'turc');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Turkmen_tk', 'Turkmène');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Twi_tw', 'Twi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Ukrainian_uk', 'ukrainien');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Urdu_ur', 'Ourdou');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Uyghur_ug', 'Uyghur');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Uzbek_uz', 'Uzbek');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Vietnamese_vi', 'vietnamien');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Walloon_wa', 'Wallon');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Welsh_cy', 'gallois');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Western Frisian_fy', 'Frison occidental');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Xhosa_xh', 'Xhosa');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Yiddish_yi', 'yiddish');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Yoruba_yo', 'Yoruba');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Zulu_zu', 'zoulou');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_available_data', 'Aucune donnée disponible à afficher.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'my_wallet_', 'Mon portefeuille');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_have_bought_products', 'Vous avez acheté un produit');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sale_products', 'Vente produits');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'entire_site', 'Site entier');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'banned_for_violating', 'Vous avez été interdit de violer nos conditions d\'utilisation. ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'were_banned_from', 'Oups, vous avez été banni de {Nom de site}');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'contact_us_more_details', 'S\'il vous plaît {contact_us} pour plus de détails.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pdf_file', 'Joindre un fichier PDF');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_file', 'Certificat');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_currently_working', 'Travaillez-vous actuellement?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'am_looking_to_work', 'Non je cherche à travailler');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'am_looking_for_employees', 'Oui je cherche des employés');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'products_for_sale', 'Produits à vendre');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_notifications_because_you_were_banned', 'Vous ne pouvez pas voir vos notifications parce que vous avez été banni');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_messages_because_you_were_banned', 'Vous ne pouvez pas voir vos messages parce que vous avez été interdit');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_requests_because_you_were_banned', 'Vous ne pouvez pas voir vos demandes parce que vous avez été interdit');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'withdrawal', 'Retrait');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'successfully_received_from', 'L\'argent a été reçu avec succès de');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'terms_of_use_page', '<H4> 1- Écrivez vos conditions d\'utilisation ici. </ H4>
      ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'privacy_policy_page', '');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'about_page', '<H4> 1- Écrivez sur votre site web ici. </ H4> Lorem Ipsum Dolor Sit Amet, Consectetur Adipisuring Elit, SED Do Eiusmod Tempor Incididunt UT Labore et Dolore Magna Aliqua. ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_terms_page', '<H4> 1- Écrivez vos conditions d\'utilisation ici. </ H4>
      ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'added_review_to_your_product', 'examiné votre produit');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'coinbase', 'Coinbase');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'product_purchase', 'Achat de produit');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sold_a_product', 'Vente de produits');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'describe_your_review', 'Décrivez votre avis ici ..');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'related_prods', 'Produits connexes');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_open_work', 'Etes-vous sûr que vous voulez supprimer?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_services', 'Êtes-vous sûr de vouloir supprimer ces services?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_find_job_at', 'Rechercher, trouver et postuler aux opportunités d\'emploi à');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'connect_with_friends', 'Connectez-vous avec des amis!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'login_connect_friends', 'Connectez-vous à votre compte {Site_Name} et connectez-vous avec vos amis!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remember_device', 'Rappelez-vous cet appareil');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'register_create_account', 'Créez votre compte {Site_Name}!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_upgrade_to_upload', 'Just Pro utilisateurs peuvent télécharger s\'il vous plaît mettre à niveau vers Pro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'type_something_to_post', 'Votre contenu post ne peut pas être vide.');
        } else if ($value == 'german') {
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_must_add_text_or_image_first', 'Sie müssen einen Text oder ein Bild hinzufügen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_to_cart', 'In den Warenkorb legen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remove_from_cart', 'Aus dem Warenkorb entfernen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_from_wallet', 'Bezahlen von Brieftasche');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_top_up_wallet', 'Sie haben nicht genug Gleichgewicht zum Kauf, bitte tippen Sie bitte Ihre Brieftasche auf.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_to_upgrade', 'Sie dürfen gerade auf ein Pro-Mitglied ein Upgrade eingehen.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_to_fund', 'Du bist dabei, zu spenden.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'amount_can_not_empty', 'Betrag ist erforderlich');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'fund_not_found', 'Die Finanzierung wird nicht gefunden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_successfully_done', 'Zahlung erfolgreich gemacht, danke!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'buy_now', 'Kaufe jetzt');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_item', 'Gesamtstückeinheiten');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_item_not_empty', 'Artikeleinheiten sind erforderlich');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'currently_unavailable', 'Momentan nicht verfügbar.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'checkout', 'Kasse');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_items_found', 'Keine Elemente gefunden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total', 'Gesamt');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'my_addresses', 'Meine Adressen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new', 'Neue hinzufügen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_address', 'Neue Adresse hinzufügen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_added', 'Ihre Adresse wurde erfolgreich hinzugefügt');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_address', 'Löschen Sie Ihre Adresse');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_address', 'Möchten Sie diese Adresse sicher, dass Sie diese Adresse löschen möchten?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_address', 'Adresse bearbeiten');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_edited', 'Ihre Adresse wurde erfolgreich bearbeitet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_add_address', 'Bitte fügen Sie eine neue Adresse hinzu');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_address', 'Bitte wählen Sie eine Adresse aus');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_alert', 'Zahlungsalarm');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'purchase_the_items', 'Sie können die Artikel kaufen, möchten Sie fortfahren?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'shopping_cart', 'Einkaufswagen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'items', 'Produkte');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'back_to_shop', 'Zurück zum Laden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'some_products_units', 'Einige Produkte sind nicht auf Lager.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_can_not_be_empty', 'Adresse kann nicht leer sein');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_not_found', 'Adresse nicht gefunden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'card_is_empty', 'Einkaufswagen ist leer');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_order_has_been_placed_successfully', 'Ihre Bestellung wurde erfolgreich platziert');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'purchased', 'Gekauft');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_purchased_found', 'Es wurden keine gekauften Elemente gefunden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order', 'Befehl');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'download_invoice', 'Download Rechnung');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'id_can_not_empty', 'ID ist erforderlich');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_are_not_purchased', 'Sie haben noch nicht gekauft.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order_not_found', 'Bestellen nicht gefunden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order_details', 'Bestelldetails');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'write_review', 'Bewertung schreiben');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'request_refund', 'Eine Rückerstattung anfordern');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_details', 'Verfolgungsdetails');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delivery_address', 'Lieferadresse');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'if_the_order_status', 'Wenn der Bestellstatus nicht innerhalb von 60 Tagen ab dem Bestelldatum angeliefert wurde, wird es automatisch an \"geliefert\" gesendet.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'if_the_order_delivered', 'Wenn die Bestellung nicht tatsächlich geliefert wurde, kann der Käufer eine Rückerstattung anfordern.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'placed', 'Platziert');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payments', 'Zahlungen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'subtotal', 'Zwischensumme');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sale_invoice', 'Verkauf Rechnung.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seller_name', 'Name des Verkäufers');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seller_email', 'Verkäufer-E-Mail.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invoice_to', 'Rechnung an');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_details', 'Zahlungsdetails');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_due', 'Insgesamt fällig.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'bank_name', 'Bank Name');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invoice', 'Rechnung');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'item', 'Artikel');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'orders', 'Aufträge');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_orders_found', 'Keine Bestellungen gefunden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'products', 'Produkte');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'qty', 'Attiert');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'canceled', 'Abgesagt');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'accepted', 'Akzeptiert');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'packed', 'Verpackt');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'shipped', 'Geliefert');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'commission', 'Kommission');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'final_price', 'Endgültiger Preis');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_number', 'Auftragsnummer, Frachtnummer, Sendungscode');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'link', 'Verknüpfung');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_info_has_been_saved_successfully', 'Tracking-Info wurde erfolgreich gespeichert');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_url_can_not_be_empty', 'Tracking-URL kann nicht leer sein');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_number_can_not_be_empty', 'Die Tracking-Nummer kann nicht leer sein');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_enter_valid_url', 'Bitte geben Sie eine gültige URL ein');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'site_url', 'Seiten-URL');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delivered', 'Geliefert');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_explain_the_reason', 'Bitte erkläre den Grund');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_request_is_under_review', 'Ihre Anfrage ist überprüft, wir kontaktieren Sie einmal mit Ihnen.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review', 'Rezension');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'submit', 'einreichen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review_can_not_be_empty', 'Der Überprüfen des Inhalts ist erforderlich.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'rating_can_not_be_empty', 'Bewertung kann nicht leer sein');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review_has_been_sent_successfully', 'Ihre Überprüfung wurde eingereicht.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'admin_status_changed', 'Der Bestellstatus wurde geändert');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'new_orders_has_been_placed', 'Neue Bestellungen wurden platziert');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'added_tracking_info', 'Tracking-Info hinzugefügt.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'product_approved', 'Ihr Produkt wurde genehmigt');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_product_is_under_review', 'Ihr Produkt wird derzeit überprüft.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tweet', 'Tweet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask', 'Fragen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'write_answer', 'Schreiben Sie eine Antwort und drücken Sie die Eingabetaste');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'reply_to_answer', 'Antwort auf Antwort');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'answered_your_question', 'Antwortete deine Frage.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'replied_to_answer', 'antwortete auf deine Antwort');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_question', 'Mochte deine Frage.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_answer', 'mochte deine Antwort');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'answer_mention', 'erwähnte Sie auf einer Antwort');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'question_mention', 'erwähnte Sie eine Frage');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'verified_purchase', 'Geprüfter Kauf.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_reviews_found', 'Keine Bewertungen gefunden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask_anonymously', 'Anonym Fragen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask_friend', 'Frag einen Freund');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_for_friends', 'Suche nach Freunden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'askfm_box_placeholder', 'Was, wann, warum ... fragen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'question_can_not_empty', 'Frage ist erforderlich.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_who_you_want_ask', 'Bitte wählen Sie aus, wer Sie fragen möchten');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'asked_you_a_question', 'fragte dir eine Frage');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'trending_questions', 'Fragen zum Trend');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'people_liked_question', 'Die Leute haben diese Frage gefallen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'users_liked_answer', 'Die Leute haben diese Antwort mochten');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_answers_found', 'Keine Antworten zu zeigen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_header_people', 'Suche nach Personen und #hashtags');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'questions', 'Fragen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tweets', 'Tweets');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'trending_tweets', 'Trending-Tweets.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'people_liked_tweet', 'Die Leute haben diesen Tweet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_tweet', 'mochte deinen Tweet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_a_file_to_upload', 'Bitte wählen Sie eine Datei zum Hochladen aus');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'unlock_content_post_text', 'Entsperren Sie diesen Inhalt, indem Sie ein Patron werden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'join_now', 'Jetzt beitreten');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patreon_membership_price', 'Patreon-Mitgliedschaftspreis.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience', 'Erfahrung');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_experience', 'Neue Erfahrung hinzufügen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'company_name', 'Name der Firma');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'employment_type', 'Beschäftigungsverhältnis');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'full_time', 'Vollzeit');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'part_time', 'Teilzeit');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'self_employed', 'Selbstständiger');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'freelance', 'Freiberuflich');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'contract', 'Vertrag');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'internship', 'Praktikum');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'apprenticeship', 'Ausbildung');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seasonal', 'Saisonal');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'industry', 'Industrie');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'title_empty', 'Bitte geben Sie einen Titel ein');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'company_name_empty', 'Bitte geben Sie einen Firmennamen ein');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'employment_type_empty', 'Bitte geben Sie einen Beschäftigungsart ein');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'location_empty', 'Bitte geben Sie einen Ort ein');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'start_date_empty', 'Bitte geben Sie ein Startdatum ein');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'industry_empty', 'Bitte geben Sie eine Branche ein');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'description_empty', 'Bitte geben Sie eine Beschreibung ein');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_choose_correct_experience_date', 'Bitte wählen Sie ein korrektes Datum.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience_successfully_created', 'Erfahrung erfolgreich erstellt.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'valid_link', 'Bitte geben Sie einen gültigen Link ein');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_experience', 'Löschen Sie Ihre Erfahrungen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_experience', 'Möchten Sie diese Erfahrung sicher, dass Sie diese Erfahrung löschen möchten?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_experience', 'Erfahrung bearbeiten.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_not_owner', 'Sie sind nicht der Besitzer, Sie können diese Aktion anwenden.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience_successfully_updated', 'Erfahrung erfolgreich aktualisiert.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certifications', 'Zertifizierungen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'licenses_certifications', 'Lizenzen & Zertifikate.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_certification', 'Neues Zertifikat hinzufügen.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issuing_organization', 'Ausstellende Organisation');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'credential_id', 'Anmeldeinformations-ID.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'credential_url', 'Anmeldeinformations-URL.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issuing_organization_empty', 'Bitte geben Sie eine ausstellende Organisation ein');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issue_date', 'Ausgabedatum');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'expiration_date', 'Ablaufdatum');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issue_date_empty', 'Bitte geben Sie das ausstellende Datum ein.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'name_empty', 'Bitte geben Sie einen Namen ein');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_successfully_created', 'Ihr Zertifikat wurde erstellt.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_certification', 'Löschen Sie Ihr Zertifikat');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_certification', 'Möchten Sie dieses Zertifikat sicher löschen?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_certification', 'Zertifikat bearbeiten.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_successfully_updated', 'Ihr Zertifikat wurde aktualisiert.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'projects', 'Projekte');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_project', 'Neues Projekt hinzufügen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_name', 'Projektname');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'associated_with', 'Verknüpft mit');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_url', 'Projekt-URL');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_successfully_added', 'Ihr Projekt wurde hinzugefügt.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_project', 'Löschen Sie Ihr Projekt');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_project', 'Möchten Sie dieses Projekt sicher löschen?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_project', 'Projekt bearbeiten.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_successfully_updated', 'Ihr Projekt wurde aktualisiert.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'skills', 'Fähigkeiten');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'languages', 'Sprachen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to', 'Geöffnet für');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'finding_a_job', 'Einen neuen Job finden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'providing_services', 'Erbringung von Dienstleistungen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'hiring', 'Einstellung');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_job_preferences', 'Jobeinstellungen hinzufügen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tell_us_kind_work', 'Sagen Sie uns, welche Art von Arbeit Sie offen sind');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'workplaces', 'Arbeitsplätze.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_title', 'Berufsbezeichnungen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'on_site', 'Vor Ort');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'hybrid', 'Hybride');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remote', 'Fernbedienung');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_types', 'Arbeitstypen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'temporary', 'Vorübergehend');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_location', 'Arbeitsplatz');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Job_title_empty', 'Die Arbeitstitel kann nicht leer sein');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_location_empty', 'Der Jobstandort kann nicht leer sein');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'workplaces_empty', 'Bitte wählen Sie einen Arbeitsplatz aus');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_type_empty', 'Bitte wählen Sie einen Jobtyp aus');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences_saved_successfully', 'Die Jobeinstellungen wurden aktualisiert.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to_work', 'Offen für die Arbeit.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'see_all_details', 'Alle Details anzeigen.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences', 'Stellenpräferenzen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'set_up_services_page', 'Lassen Sie uns Ihre Service-Seite einrichten');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services', 'Dienstleistungen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_empty', 'Dienste können nicht leer sein');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_saved_successfully', 'Dienstleistungen wurden gespeichert.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_provided', 'Dienstleistungen zur Verfügung gestellt');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invalid_id', 'Ungültige ID');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_edited_successfully', 'Dienstleistungen wurden aktualisiert.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_job_preferences', 'Jobeinstellungen bearbeiten.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences_edited_successfully', 'Stellenvoreinstellungen wurden bearbeitet.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_services', 'Mehr Dienstleistungen laden.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tiers', 'Reihen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_offer_patrons', 'Wählen Sie aus, was Sie Ihren Gästen anbieten sollen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_tier', 'Tier hinzufügen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_title', 'Titel Titel');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_price', 'Tierpreis');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_image', 'Tier-Image');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_description', 'Tierbeschreibung.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'benefits', 'Leistungen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_without_audio_video', 'Chatten Sie ohne Audio- und Videoanruf');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_with_audio_without_video', 'Chatten Sie mit Audio-Anruf und ohne Videoanruf');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_without_audio_with_video', 'Chat ohne Audioanruf und mit Videoanruf');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_with_audio_video', 'Chat mit Audio- und Videoanruf');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat', 'Plaudern');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'live_stream', 'Liveübertragung');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'price_empty', 'Der Preis kann nicht leer sein');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'benefits_empty', 'Vorteile können nicht leer sein');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_chat_type', 'Bitte wählen Sie den Chat-Typ aus');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_added_successfully', 'Tier erfolgreich hinzugefügt');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_tier', 'Tier bearbeiten');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_updated_successfully', 'Tier erfolgreich aktualisiert.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_tier', 'Löschen Sie Ihren Tier');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_tier', 'Bist du sicher, dass du diesen Tier löschen willst?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patron', 'Patron');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patrons', 'Gönner');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_you_may_know', 'Dienstleistungen, die Sie mögen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to_work_posts', 'Offen für Arbeitsbeiträge');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Afrikaans_af', 'Afrikaans');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Albanian_sq', 'albanisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Amharic_am', 'Amharisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Arabic_ar', 'Arabisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Aragonese_an', 'Aragonesen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Armenian_hy', 'Armenisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Asturian_ast', 'Asturianisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Azerbaijani_az', 'Aserbaidschani.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Basque_eu', 'baskisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Belarusian_be', 'Belarussisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bengali_bn', 'Bengali');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bosnian_bs', 'bosnisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Breton_br', 'Bretonisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bulgarian_bg', 'bulgarisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Catalan_ca', 'katalanisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Central Kurdish_ckb', 'Zentralkurdish.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Chinese_zh', 'Chinesisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Corsican_co', 'Korsikan');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Croatian_hr', 'kroatisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Czech_cs', 'Tschechisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Danish_da', 'dänisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Dutch_nl', 'Niederländisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'English_en', 'Englisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Esperanto_eo', 'Esperanto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Estonian_et', 'estnisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Faroese_fo', 'Faroese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Filipino_fil', 'Philippinisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Finnish_fi', 'finnisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'French_fr', 'Französisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Galician_gl', 'galizisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Georgian_ka', 'georgisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'German_de', 'Deutsch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Greek_el', 'griechisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Guarani_gn', 'Guarani.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Gujarati_gu', 'Gujarati');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hausa_ha', 'Hausa.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hawaiian_haw', 'hawaiisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hebrew_he', 'hebräisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hindi_hi', 'Hindi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hungarian_hu', 'ungarisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Icelandic_is', 'isländisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Indonesian_id', 'Indonesisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Interlingua_ia', 'Interlingua');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Irish_ga', 'irisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Italian_it', 'Italienisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Japanese_ja', 'japanisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kannada_kn', 'Kannada.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kazakh_kk', 'Kasakh.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Khmer_km', 'Khmer');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Korean_ko', 'Koreanisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kurdish_ku', 'kurdisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kyrgyz_ky', 'Kirgisisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lao_lo', 'Lao.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Latin_la', 'Latein');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Latvian_lv', 'lettisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lingala_ln', 'Lingala');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lithuanian_lt', 'litauisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Macedonian_mk', 'mazedonisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Malay_ms', 'malaiisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Malayalam_ml', 'Malayalam');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Maltese_mt', 'maltesisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Marathi_mr', 'Marathi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Mongolian_mn', 'mongolisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Nepali_ne', 'Nepali.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian_no', 'norwegisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian Bokmål_nb', 'Norwegisch Bokmål.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian Nynorsk_nn', 'Norwegisches Nynorsk.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Occitan_oc', 'Okzitanisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Oriya_or', 'Oriya.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Oromo_om', 'Oromo.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Pashto_ps', 'Pashto.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Persian_fa', 'persisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Polish_pl', 'Polieren');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Portuguese_pt', 'Portugiesisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Punjabi_pa', 'Punjabi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Quechua_qu', 'Quechua');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Romanian_ro', 'rumänisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Romansh_rm', 'Romansh');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Russian_ru', 'Russisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Scottish Gaelic_gd', 'schottisch Gälisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Serbian_sr', 'serbisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Serbo_sh', 'Serbo.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Shona_sn', 'Shona.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sindhi_sd', 'Sindhi.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sinhala_si', 'Sinhala');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Slovak_sk', 'slowakisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Slovenian_sl', 'Slowenisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Somali_so', 'somali');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Southern Sotho_st', 'Südlich sotho.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Spanish_es', 'Spanisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sundanese_su', 'Sonnendanese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Swahili_sw', 'Swahili');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Swedish_sv', 'Schwedisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tajik_tg', 'Tadschik');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tamil_ta', 'Tamilisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tatar_tt', 'Tatar.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Telugu_te', 'Telugu');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Thai_th', 'Thailändisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tigrinya_ti', 'Tigrinya.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tongan_to', 'Tanganer');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Turkish_tr', 'Türkisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Turkmen_tk', 'Turkmen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Twi_tw', 'Twi.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Ukrainian_uk', 'ukrainisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Urdu_ur', 'Urdu');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Uyghur_ug', 'Uyghur.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Uzbek_uz', 'Usbekisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Vietnamese_vi', 'Vietnamesisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Walloon_wa', 'wallonisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Welsh_cy', 'Walisisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Western Frisian_fy', 'Westernfriesian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Xhosa_xh', 'Xhosa.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Yiddish_yi', 'Jiddisch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Yoruba_yo', 'Yoruba.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Zulu_zu', 'Zulu-');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_available_data', 'Keine verfügbaren Daten zum Anzeigen.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'my_wallet_', 'Mein Geldbeutel');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_have_bought_products', 'Sie haben ein Produkt gekauft');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sale_products', 'Verkaufsprodukte');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'entire_site', 'Ganze Seite');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'banned_for_violating', 'Sie wurden verboten, um unsere Nutzungsbedingungen zu verletzen. ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'were_banned_from', 'Oops, du wurdest von {site_name} gesperrt');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'contact_us_more_details', 'Bitte {contact_us} für weitere Details.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pdf_file', 'PDF-Datei anhängen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_file', 'Zertifikat');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_currently_working', 'arbeitest du gerade?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'am_looking_to_work', 'Nein, ich suche ich');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'am_looking_for_employees', 'Ja, ich suche Mitarbeiter');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'products_for_sale', 'Produkte zum Verkauf.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_notifications_because_you_were_banned', 'Sie können Ihre Benachrichtigungen nicht anzeigen, weil Sie gesperrt wurden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_messages_because_you_were_banned', 'Sie können Ihre Nachrichten nicht anzeigen, da Sie gesperrt wurden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_requests_because_you_were_banned', 'Sie können Ihre Anfragen nicht anzeigen, weil Sie gesperrt wurden');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'withdrawal', 'Rückzug');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'successfully_received_from', 'Geld wurde erfolgreich von erhalten');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'terms_of_use_page', '<H4> 1- Schreiben Sie hier Ihre Nutzungsbedingungen. </ H4>
      ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'privacy_policy_page', '');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'about_page', '<H4> 1- Schreiben Sie hier über Ihre Website. </ H4> Lorem Ipsum Dolor Sit AMET, Konsektor-Adipialisierung Elit, SED do EiusMod Tencipe Incididunts Ut Labore et dolore Magna Aliqua. ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_terms_page', '<H4> 1- Schreiben Sie hier Ihre Nutzungsbedingungen. </ H4>
      ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'added_review_to_your_product', 'Überprüfte dein Produkt.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'coinbase', 'Coinbase');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'product_purchase', 'Produktkauf.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sold_a_product', 'Produktverkauf.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'describe_your_review', 'Beschreiben Sie Ihre Bewertung hier ..');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'related_prods', 'Verwandte Produkte');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_open_work', 'Sind Sie sicher, dass Sie löschen möchten?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_services', 'Sind Sie sicher, dass Sie diese Dienste löschen möchten?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_find_job_at', 'Suchen, finden und anwenden Sie sich für Stellenangebote an');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'connect_with_friends', 'Verbinden Sie sich mit Freunden!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'login_connect_friends', 'Melden Sie sich in Ihr {site_name} Konto an und verbinden Sie sich mit Ihren Freunden!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remember_device', 'erinnern Sie sich an dieses Gerät');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'register_create_account', 'Erstellen Sie Ihr {site_name} Konto!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_upgrade_to_upload', 'Nur Pro-Benutzer können hochladen, bitte aktualisieren auf pro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'type_something_to_post', 'Ihr Postinhalt kann nicht leer sein.');
        } else if ($value == 'italian') {
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_must_add_text_or_image_first', 'Devi aggiungere un testo o un\'immagine');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_to_cart', 'Aggiungi al carrello');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remove_from_cart', 'Rimuovi dal carrello');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_from_wallet', 'Pagare con il portafoglio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_top_up_wallet', 'Non hai abbastanza equilibrio da acquistare, per favore rabboccare il tuo portafoglio.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_to_upgrade', 'Stai per eseguire l\'aggiornamento a un membro professionale.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_to_fund', 'Stai per donare.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'amount_can_not_empty', 'L\'importo è richiesto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'fund_not_found', 'Il finanziamento non è stato trovato');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_successfully_done', 'Pagamento fatto con successo, grazie!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'buy_now', 'Acquista ora');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_item', 'Unità di articoli totali');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_item_not_empty', 'Le unità degli articoli sono richieste');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'currently_unavailable', 'Attualmente non disponibile.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'checkout', 'Guardare');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_items_found', 'Nessun articolo trovato');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total', 'Totale');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'my_addresses', 'I miei indirizzi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new', 'Aggiungere nuova');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_address', 'Aggiungi un nuovo indirizzo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_added', 'Il tuo indirizzo è stato aggiunto con successo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_address', 'Elimina il tuo indirizzo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_address', 'Sei sicuro di voler eliminare questo indirizzo?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_address', 'Modifica indirizzo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_edited', 'Il tuo indirizzo è stato modificato con successo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_add_address', 'Si prega di aggiungere un nuovo indirizzo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_address', 'Si prega di selezionare un indirizzo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_alert', 'Avviso di pagamento');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'purchase_the_items', 'Stai per acquistare gli articoli, vuoi procedere?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'shopping_cart', 'Carrello della spesa');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'items', 'Elementi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'back_to_shop', 'Torna a Shop.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'some_products_units', 'Alcuni prodotti sono esauriti.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_can_not_be_empty', 'L\'indirizzo non può essere vuoto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_not_found', 'Indirizzo non trovato.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'card_is_empty', 'Il carrello è vuoto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_order_has_been_placed_successfully', 'Il tuo ordine è stato posizionato con successo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'purchased', 'Acquistato');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_purchased_found', 'Nessun articolo acquistato trovato');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order', 'Ordine');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'download_invoice', 'Scarica Fattura');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'id_can_not_empty', 'L\'ID è richiesto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_are_not_purchased', 'Non hai ancora acquistato.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order_not_found', 'Ordine non trovato');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order_details', 'Dettagli ordine');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'write_review', 'Scrivere una recensione');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'request_refund', 'Richiedere un rimborso');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_details', 'Tracciamento dei dettagli');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delivery_address', 'Indirizzo di consegna');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'if_the_order_status', 'Se lo stato dell\'ordine non è stato impostato su Fornito entro 60 giorni dalla data dell\'ordine, verrà automaticamente inviato a \"consegnato\".');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'if_the_order_delivered', 'Se l\'ordine non è stato effettivamente consegnato, l\'acquirente può richiedere un rimborso.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'placed', 'Posto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payments', 'Pagamenti');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'subtotal', 'totale parziale');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sale_invoice', 'Fattura di vendita');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seller_name', 'Nome del venditore');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seller_email', 'Email del venditore');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invoice_to', 'Fattura');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_details', 'Dettagli di pagamento');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_due', 'Totale dovuto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'bank_name', 'nome della banca');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invoice', 'Fattura');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'item', 'Elemento');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'orders', 'Ordini');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_orders_found', 'Nessun ordine trovato');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'products', 'Prodotti');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'qty', 'Qty.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'canceled', 'Annullato');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'accepted', 'Accettato');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'packed', 'Confezionato');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'shipped', 'Spedito');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'commission', 'Commissione');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'final_price', 'Prezzo finale');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_number', 'Numero di identificazione');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'link', 'Collegamento');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_info_has_been_saved_successfully', 'Le informazioni di tracciamento sono state salvate con successo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_url_can_not_be_empty', 'L\'URL di tracciamento non può essere vuoto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_number_can_not_be_empty', 'Il numero di tracciamento non può essere vuoto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_enter_valid_url', 'Per favore, inserisci un URL valido');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'site_url', 'Indirizzo del sito');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delivered', 'Consegnato');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_explain_the_reason', 'Si prega di spiegare la ragione');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_request_is_under_review', 'La tua richiesta è in fase di revisione, ti contattiamo una volta.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review', 'Revisione');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'submit', 'Invia');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review_can_not_be_empty', 'Il contenuto della revisione è richiesto.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'rating_can_not_be_empty', 'La valutazione non può essere vuota');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review_has_been_sent_successfully', 'La tua recensione è stata presentata.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'admin_status_changed', 'Lo stato dell\'ordine è stato modificato');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'new_orders_has_been_placed', 'I nuovi ordini sono stati collocati');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'added_tracking_info', 'Aggiunto informazioni di monitoraggio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'product_approved', 'Il tuo prodotto è stato approvato');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_product_is_under_review', 'Il tuo prodotto è attualmente in esame.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tweet', 'Tweet.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask', 'Chiedere');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'write_answer', 'Scrivi una risposta e premi Invio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'reply_to_answer', 'Rispondi a Risposta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'answered_your_question', 'ha risposto alla tua domanda');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'replied_to_answer', 'ha risposto alla tua risposta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_question', 'è piaciuta la tua domanda');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_answer', 'è piaciuta la tua risposta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'answer_mention', 'ti ho detto su una risposta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'question_mention', 'ti ha detto su una domanda');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'verified_purchase', 'Acquisto verificato');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_reviews_found', 'Nessuna recensione trovata');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask_anonymously', 'Chiedere anonimamente');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask_friend', 'Chiedi a un amico');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_for_friends', 'Cerca amici.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'askfm_box_placeholder', 'Cosa, quando, perché ... chiedi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'question_can_not_empty', 'La domanda è richiesta.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_who_you_want_ask', 'Si prega di selezionare chi vuoi chiedere');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'asked_you_a_question', 'ti ha fatto una domanda');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'trending_questions', 'Domande di tendenza');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'people_liked_question', 'Alla gente è piaciuta questa domanda');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'users_liked_answer', 'Alla gente è piaciuta questa risposta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_answers_found', 'Nessuna risposta da mostrare');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_header_people', 'Cerca persone e #hashtags');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'questions', 'Domande');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tweets', 'Tweets.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'trending_tweets', 'Trending Tweet.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'people_liked_tweet', 'A gente è piaciuto questo tweet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_tweet', 'Mi è piaciuto il tuo Tweet.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_a_file_to_upload', 'Si prega di selezionare un file da caricare');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'unlock_content_post_text', 'Sblocca questo contenuto diventando un patrono');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'join_now', 'Iscriviti adesso');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patreon_membership_price', 'Prezzo di appartenenza Patreon.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience', 'Esperienza');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_experience', 'Aggiungi una nuova esperienza');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'company_name', 'Nome della ditta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'employment_type', 'Tipo di impiego');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'full_time', 'Tempo pieno');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'part_time', 'Part time');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'self_employed', 'Lavoratore autonomo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'freelance', 'Libero professionista');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'contract', 'Contrarre');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'internship', 'tirocinio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'apprenticeship', 'Apprendistato');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seasonal', 'di stagione');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'industry', 'Industria');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'title_empty', 'Si prega di inserire un titolo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'company_name_empty', 'Si prega di inserire un nome aziendale');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'employment_type_empty', 'Si prega di inserire un tipo di occupazione');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'location_empty', 'Si prega di inserire una posizione');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'start_date_empty', 'Si prega di inserire una data di inizio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'industry_empty', 'Per favore inserisci un settore');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'description_empty', 'Si prega di inserire una descrizione');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_choose_correct_experience_date', 'Si prega di scegliere una data corretta.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience_successfully_created', 'Esperienza creata con successo.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'valid_link', 'Si prega di inserire un link valido');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_experience', 'Elimina la tua esperienza');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_experience', 'Sei sicuro di voler cancellare questa esperienza?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_experience', 'Edit Experience.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_not_owner', 'Non sei il proprietario, puoi applicare questa azione.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience_successfully_updated', 'Esperienza aggiornata con successo.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certifications', 'Certificazioni');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'licenses_certifications', 'Licenze e certificati.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_certification', 'Aggiungi un nuovo certificato');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issuing_organization', 'Organizzazione di emissione');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'credential_id', 'ID Credenziali');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'credential_url', 'URL delle credenziali');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issuing_organization_empty', 'Si prega di inserire un\'organizzazione di emissione');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issue_date', 'Data di rilascio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'expiration_date', 'Data di scadenza');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issue_date_empty', 'Si prega di inserire la data di emissione.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'name_empty', 'Si prega di inserire un nome');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_successfully_created', 'Il tuo certificato è stato creato.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_certification', 'Elimina il tuo certificato');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_certification', 'Sei sicuro di voler cancellare questo certificato?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_certification', 'Modifica certificato');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_successfully_updated', 'Il tuo certificato è stato aggiornato.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'projects', 'Progetti');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_project', 'Aggiungi un nuovo progetto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_name', 'Nome del progetto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'associated_with', 'Associato a');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_url', 'URL del progetto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_successfully_added', 'Il tuo progetto è stato aggiunto.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_project', 'Elimina il tuo progetto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_project', 'Sei sicuro di voler cancellare questo progetto?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_project', 'Modifica progetto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_successfully_updated', 'Il tuo progetto è stato aggiornato.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'skills', 'Abilità');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'languages', 'Le lingue');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to', 'Aperto a');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'finding_a_job', 'Trovare un nuovo lavoro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'providing_services', 'Fornitura di servizi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'hiring', 'Assumere');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_job_preferences', 'Aggiungi preferenze di lavoro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tell_us_kind_work', 'Dicci che tipo di lavoro sei aperto a');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'workplaces', 'Ambienti di lavoro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_title', 'Titolo di lavoro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'on_site', 'Sul posto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'hybrid', 'Ibrido');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remote', 'A distanza');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_types', 'Tipi di lavoro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'temporary', 'Temporaneo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_location', 'Luogo di lavoro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Job_title_empty', 'Il titolo del lavoro non può essere vuoto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_location_empty', 'La posizione del lavoro non può essere vuota');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'workplaces_empty', 'Si prega di selezionare un luogo di lavoro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_type_empty', 'Si prega di selezionare un tipo di lavoro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences_saved_successfully', 'Le preferenze del lavoro sono state aggiornate.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to_work', 'Aperto al lavoro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'see_all_details', 'Vedi tutti i dettagli');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences', 'Preferenze di lavoro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'set_up_services_page', 'Configura la pagina dei servizi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services', 'Servizi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_empty', 'I servizi non possono essere vuoti');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_saved_successfully', 'I servizi sono stati salvati.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_provided', 'Servizio fornito');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invalid_id', 'ID non valido');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_edited_successfully', 'I servizi sono stati aggiornati.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_job_preferences', 'Modifica le preferenze del lavoro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences_edited_successfully', 'Le preferenze di lavoro sono state modificate.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_services', 'Carica più servizi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tiers', 'Livelli');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_offer_patrons', 'Scegli cosa offrire ai tuoi clienti');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_tier', 'Aggiungi Tier.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_title', 'Titolo di livello');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_price', 'Prezzo di livello');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_image', 'Immagine di livello');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_description', 'Descrizione di livello');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'benefits', 'Benefici');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_without_audio_video', 'Chat senza audio e videochiamata');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_with_audio_without_video', 'Chat con audio chiamata e senza videochiamata');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_without_audio_with_video', 'Chat senza chiamata audio e con videochiamata');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_with_audio_video', 'Chat con audio e videochiamata');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat', 'Chiacchierata');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'live_stream', 'Trasmissione in diretta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'price_empty', 'Il prezzo non può essere vuoto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'benefits_empty', 'I vantaggi non possono essere vuoti');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_chat_type', 'Si prega di selezionare il tipo di chat');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_added_successfully', 'Tier ha aggiunto con successo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_tier', 'Modifica il livello');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_updated_successfully', 'Tier aggiornato con successo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_tier', 'Elimina il tuo livello');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_tier', 'Sei sicuro di voler cancellare questo livello?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patron', 'Patrono');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patrons', 'Patroni');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_you_may_know', 'Servizi che potrebbero piacere');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to_work_posts', 'Aperto ai posti di lavoro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Afrikaans_af', 'afrikaans');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Albanian_sq', 'albanese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Amharic_am', 'Amharic.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Arabic_ar', 'Arabo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Aragonese_an', 'aragonese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Armenian_hy', 'armeno');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Asturian_ast', 'Asturiano');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Azerbaijani_az', 'Azerbaijani.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Basque_eu', 'Basco');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Belarusian_be', 'Bielorusso');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bengali_bn', 'bengalese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bosnian_bs', 'Bosniaco');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Breton_br', 'bretone');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bulgarian_bg', 'bulgaro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Catalan_ca', 'catalano');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Central Kurdish_ckb', 'Kurdish centrale.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Chinese_zh', 'Cinese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Corsican_co', 'Corsican.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Croatian_hr', 'croato');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Czech_cs', 'ceco');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Danish_da', 'danese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Dutch_nl', 'olandese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'English_en', 'inglese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Esperanto_eo', 'esperanto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Estonian_et', 'Estone');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Faroese_fo', 'Faroese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Filipino_fil', 'Filippino');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Finnish_fi', 'finlandese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'French_fr', 'francese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Galician_gl', 'galiziano');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Georgian_ka', 'georgiano');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'German_de', 'Tedesco');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Greek_el', 'greco');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Guarani_gn', 'Guarani');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Gujarati_gu', 'Gujarati.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hausa_ha', 'Hausa.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hawaiian_haw', 'hawaiano');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hebrew_he', 'ebraico');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hindi_hi', 'hindi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hungarian_hu', 'ungherese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Icelandic_is', 'islandese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Indonesian_id', 'indonesiano');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Interlingua_ia', 'Interlingua.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Irish_ga', 'irlandesi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Italian_it', 'italiano');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Japanese_ja', 'giapponese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kannada_kn', 'Kannada.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kazakh_kk', 'Kazako.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Khmer_km', 'Khmer.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Korean_ko', 'coreano');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kurdish_ku', 'Kurdo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kyrgyz_ky', 'Kirghiz');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lao_lo', 'Lao.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Latin_la', 'latino');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Latvian_lv', 'lettone');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lingala_ln', 'Lingala.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lithuanian_lt', 'Lituano');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Macedonian_mk', 'macedone');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Malay_ms', 'malese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Malayalam_ml', 'Malayalam.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Maltese_mt', 'maltese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Marathi_mr', 'Marathi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Mongolian_mn', 'mongolo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Nepali_ne', 'Nepalese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian_no', 'norvegese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian Bokmål_nb', 'Norwegian Bokmål.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian Nynorsk_nn', 'Norvegese Nynorsk.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Occitan_oc', 'Occitano');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Oriya_or', 'Oriya.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Oromo_om', 'Oromo.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Pashto_ps', 'Pashto.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Persian_fa', 'persiano');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Polish_pl', 'polacco');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Portuguese_pt', 'portoghese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Punjabi_pa', 'Punjabi.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Quechua_qu', 'Quechua.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Romanian_ro', 'rumeno');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Romansh_rm', 'Romancio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Russian_ru', 'russo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Scottish Gaelic_gd', 'Gaelico Scozzese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Serbian_sr', 'serbo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Serbo_sh', 'Serbo.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Shona_sn', 'Shona.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sindhi_sd', 'Sindhi.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sinhala_si', 'Singala');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Slovak_sk', 'Slovacco');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Slovenian_sl', 'sloveno');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Somali_so', 'Somalo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Southern Sotho_st', 'Sothern Sotho.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Spanish_es', 'spagnolo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sundanese_su', 'Sundanese.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Swahili_sw', 'Swahili.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Swedish_sv', 'svedese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tajik_tg', 'Tajik.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tamil_ta', 'Tamil');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tatar_tt', 'Tatar.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Telugu_te', 'Telugu.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Thai_th', 'tailandese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tigrinya_ti', 'Tigrenya.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tongan_to', 'Tongan.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Turkish_tr', 'Turco');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Turkmen_tk', 'Turkmen.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Twi_tw', 'Twi.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Ukrainian_uk', 'ucraino');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Urdu_ur', 'Urdu');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Uyghur_ug', 'Uyghur.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Uzbek_uz', 'Uzbeko.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Vietnamese_vi', 'vietnamita');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Walloon_wa', 'Walloon.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Welsh_cy', 'gallese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Western Frisian_fy', 'Frisiano occidentale');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Xhosa_xh', 'Xhosa.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Yiddish_yi', 'yiddish');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Yoruba_yo', 'Yoruba.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Zulu_zu', 'Zuli.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_available_data', 'Nessun dato disponibile da mostrare.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'my_wallet_', 'Il mio portafoglio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_have_bought_products', 'Hai comprato un prodotto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sale_products', 'Prodotti di vendita');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'entire_site', 'Intero sito');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'banned_for_violating', 'Sei stato bannato per aver violato i nostri termini di utilizzo. ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'were_banned_from', 'Oops, sei stato bannato da {sito_name}');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'contact_us_more_details', 'Vi preghiamo di contattarci per maggiori dettagli.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pdf_file', 'Allega il file PDF.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_file', 'Certificato');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_currently_working', 'Stai lavorando attualmente?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'am_looking_to_work', 'No, sto cercando di lavorare');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'am_looking_for_employees', 'Sì, sto cercando dipendenti');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'products_for_sale', 'Prodotti in vendita.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_notifications_because_you_were_banned', 'Non puoi vedere le tue notifiche perché sei stato bannato');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_messages_because_you_were_banned', 'Non puoi visualizzare i tuoi messaggi perché sei stato bannato');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_requests_because_you_were_banned', 'Non puoi visualizzare le tue richieste perché sei stato bannato');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'withdrawal', 'Ritiro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'successfully_received_from', 'Il denaro è stato ricevuto con successo da');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'terms_of_use_page', '<h4> 1- Scrivi i tuoi termini di utilizzo qui. </ h4>
      ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'privacy_policy_page', '');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'about_page', '<H4> 1- Scrivi del tuo sito web qui. </ h4> Lorem Ipsum dolor sit Amet, Consectotur addipisicicing Elit, Sed do Eiusmod Timpor Incididunt Ut Labore et dolore Magna Aliqua. ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_terms_page', '<h4> 1- Scrivi i tuoi termini di utilizzo qui. </ h4>
      ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'added_review_to_your_product', 'Recensito il tuo prodotto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'coinbase', 'Coinbase');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'product_purchase', 'Acquisto del prodotto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sold_a_product', 'Vendita del prodotto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'describe_your_review', 'Descrivi la tua recensione qui ..');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'related_prods', 'prodotti correlati');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_open_work', 'Sei sicuro di voler eliminare?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_services', 'Sei sicuro di voler cancellare questi servizi?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_find_job_at', 'Cerca, trova e applica alle opportunità di lavoro a');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'connect_with_friends', 'Connettiti con gli amici!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'login_connect_friends', 'Accedi al tuo account {sito_name} e connettiti con i tuoi amici!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remember_device', 'Ricorda questo dispositivo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'register_create_account', 'Crea il tuo account {sito_name}!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_upgrade_to_upload', 'Solo gli utenti PRO possono caricare aggiornare a Pro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'type_something_to_post', 'Il tuo contenuto del post non può essere vuoto.');
        } else if ($value == 'portuguese') {
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_must_add_text_or_image_first', 'Você deve adicionar um texto ou imagem');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_to_cart', 'Adicionar ao carrinho');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remove_from_cart', 'Retire do carrinho');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_from_wallet', 'Pague pela Wallet.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_top_up_wallet', 'Você não tem equilíbrio suficiente para comprar, por favor suba sua carteira.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_to_upgrade', 'Você está prestes a atualizar para um membro Pro.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_to_fund', 'Você está prestes a doar.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'amount_can_not_empty', 'Quantidade é necessária');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'fund_not_found', 'Financiamento não é encontrado');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_successfully_done', 'Pagamento feito com sucesso, obrigado!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'buy_now', 'Compre');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_item', 'Unidades totais de itens');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_item_not_empty', 'Unidades de item são necessárias');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'currently_unavailable', 'Atualmente indisponivel.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'checkout', 'Confira');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_items_found', 'Nenhum item encontrado');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total', 'Total');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'my_addresses', 'Meus endereços');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new', 'Adicionar novo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_address', 'Adicionar novo endereço');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_added', 'Seu endereço foi adicionado com sucesso');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_address', 'Exclua seu endereço');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_address', 'Tem certeza de que deseja excluir este endereço?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_address', 'Editar Endereço');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_edited', 'Seu endereço foi editado com sucesso');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_add_address', 'Por favor, adicione um novo endereço');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_address', 'Por favor, selecione um endereço');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_alert', 'Alerta de pagamento');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'purchase_the_items', 'Você está prestes a comprar os itens, deseja prosseguir?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'shopping_cart', 'Carrinho de compras');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'items', 'Itens');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'back_to_shop', 'De volta à loja');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'some_products_units', 'Alguns produtos estão fora de estoque.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_can_not_be_empty', 'Endereço não pode estar vazio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_not_found', 'Endereço não encontrado');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'card_is_empty', 'carrinho esta vazio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_order_has_been_placed_successfully', 'Seu pedido foi feito com sucesso');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'purchased', 'Comprado');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_purchased_found', 'Nenhum item comprado encontrado');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order', 'Pedido');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'download_invoice', 'Baixe o Invoice');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'id_can_not_empty', 'ID é obrigatório');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_are_not_purchased', 'Você ainda não comprou.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order_not_found', 'Ordem não encontrada');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order_details', 'Detalhes do pedido');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'write_review', 'Escrever análise');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'request_refund', 'Peça um reembolso');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_details', 'Detalhes de rastreamento');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delivery_address', 'Endereço de entrega');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'if_the_order_status', 'Se o status do pedido não foi definido como entregue no prazo de 60 dias a partir da data do pedido, ele será automaticamente enviado para \"entregue\".');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'if_the_order_delivered', 'Se o pedido não foi realmente entregue, o comprador pode solicitar um reembolso.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'placed', 'Colocada');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payments', 'Pagamentos');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'subtotal', 'Subtotal');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sale_invoice', 'Fatura de venda');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seller_name', 'Nome do Vendedor');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seller_email', 'Email do vendedor');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invoice_to', 'Fatura para');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_details', 'Detalhes do pagamento');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_due', 'Total devido');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'bank_name', 'nome do banco');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invoice', 'Fatura');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'item', 'Item');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'orders', 'Pedidos');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_orders_found', 'Nenhuma encomenda encontrada');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'products', 'Produtos');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'qty', 'Qty.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'canceled', 'Cancelado');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'accepted', 'Aceitaram');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'packed', 'Embalado');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'shipped', 'Enviado');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'commission', 'Comissão');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'final_price', 'Preço final');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_number', 'Numero de rastreio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'link', 'Link');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_info_has_been_saved_successfully', 'As informações de rastreamento foram salvas com sucesso');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_url_can_not_be_empty', 'O URL de rastreamento não pode estar vazio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_number_can_not_be_empty', 'Número de rastreamento não pode estar vazio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_enter_valid_url', 'Por favor, insira um URL válido');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'site_url', 'URL do site');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delivered', 'Entregue');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_explain_the_reason', 'Por favor, explique o motivo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_request_is_under_review', 'Sua solicitação está em revisão, entramos em contato com você uma vez.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review', 'Análise');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'submit', 'Enviar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review_can_not_be_empty', 'O conteúdo de revisão é necessário.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'rating_can_not_be_empty', 'classificação não pode estar vazia');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review_has_been_sent_successfully', 'Sua avaliação foi enviada.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'admin_status_changed', 'O status do pedido foi alterado');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'new_orders_has_been_placed', 'Novas ordens foram colocadas');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'added_tracking_info', 'Acrescentou informações de rastreamento');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'product_approved', 'Seu produto foi aprovado');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_product_is_under_review', 'Seu produto está atualmente em revisão.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tweet', 'Tweet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask', 'Perguntar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'write_answer', 'Escreva uma resposta e pressione ENTER');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'reply_to_answer', 'Responder à resposta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'answered_your_question', 'Respondeu sua pergunta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'replied_to_answer', 'respondeu à sua resposta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_question', 'gostei da sua pergunta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_answer', 'gostei da sua resposta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'answer_mention', 'mencionou você em uma resposta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'question_mention', 'mencionou você em uma pergunta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'verified_purchase', 'Compra verificada.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_reviews_found', 'Nenhum comentário encontrado');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask_anonymously', 'Pergunte anonimamente');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask_friend', 'Pergunte a um amigo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_for_friends', 'Procure por amigos');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'askfm_box_placeholder', 'O que, quando, por que ... pergunte');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'question_can_not_empty', 'Pergunta é necessária.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_who_you_want_ask', 'Por favor, selecione quem você quer perguntar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'asked_you_a_question', 'Perguntou uma pergunta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'trending_questions', 'Perguntas de tendência');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'people_liked_question', 'As pessoas achou desta pergunta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'users_liked_answer', 'As pessoas gostavam dessa resposta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_answers_found', 'Sem respostas para mostrar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_header_people', 'Procurar pessoas e #hashtags');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'questions', 'Perguntas');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tweets', 'Tweets.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'trending_tweets', 'Tendências Tweets.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'people_liked_tweet', 'As pessoas gostavam desse tweet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_tweet', 'gostou do seu tweet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_a_file_to_upload', 'Por favor, selecione um arquivo para carregar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'unlock_content_post_text', 'Desbloquear este conteúdo tornando-se um patrono');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'join_now', 'Entrar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patreon_membership_price', 'Preço de associação de Patreon');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience', 'Experiência');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_experience', 'Adicionar nova experiência');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'company_name', 'Nome da empresa');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'employment_type', 'Tipo de Emprego');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'full_time', 'Tempo total');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'part_time', 'Meio período');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'self_employed', 'Trabalhadores por conta própria');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'freelance', 'Freelance');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'contract', 'Contrato');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'internship', 'Estágio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'apprenticeship', 'Aprendizagem');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seasonal', 'Sazonal');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'industry', 'Indústria');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'title_empty', 'Por favor, insira um título');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'company_name_empty', 'Por favor, insira um nome da empresa');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'employment_type_empty', 'Por favor, insira um tipo de emprego');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'location_empty', 'Por favor, digite um local');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'start_date_empty', 'Por favor insira uma data de início');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'industry_empty', 'Por favor, insira uma indústria');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'description_empty', 'Por favor, insira uma descrição');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_choose_correct_experience_date', 'Por favor, escolha uma data correta.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience_successfully_created', 'Experiência criada com sucesso.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'valid_link', 'Por favor, insira um link válido');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_experience', 'Exclua sua experiência');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_experience', 'Tem certeza de que deseja excluir essa experiência?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_experience', 'Editar experiência');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_not_owner', 'Você não é o proprietário, você pode aplicar esta ação.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience_successfully_updated', 'Experiência atualizada com sucesso.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certifications', 'Certificações.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'licenses_certifications', 'Licenças e certificados');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_certification', 'Adicionar novo certificado');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issuing_organization', 'Organização emissora');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'credential_id', 'ID de credencial');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'credential_url', 'URL de credenciais.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issuing_organization_empty', 'Por favor, insira uma organização emissora');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issue_date', 'Data de emissão');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'expiration_date', 'Data de validade');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issue_date_empty', 'Por favor, insira a data emissora.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'name_empty', 'Por favor insira um nome');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_successfully_created', 'Seu certificado foi criado.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_certification', 'Exclua seu certificado');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_certification', 'Tem certeza de que deseja excluir este certificado?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_certification', 'Editar certificado');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_successfully_updated', 'Seu certificado foi atualizado.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'projects', 'Projetos');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_project', 'Adicionar novo projeto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_name', 'Nome do Projeto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'associated_with', 'Associado com');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_url', 'URL do projeto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_successfully_added', 'Seu projeto foi adicionado.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_project', 'Exclua seu projeto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_project', 'Tem certeza de que deseja excluir este projeto?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_project', 'Editar projeto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_successfully_updated', 'Seu projeto foi atualizado.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'skills', 'Habilidades');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'languages', 'línguas');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to', 'Aberto para');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'finding_a_job', 'Encontrando um novo emprego');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'providing_services', 'Prestação de serviços');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'hiring', 'Contratando');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_job_preferences', 'Adicionar preferências de emprego');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tell_us_kind_work', 'Diga-nos que tipo de trabalho você está aberto para');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'workplaces', 'Locais de trabalho');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_title', 'Títulos de trabalho');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'on_site', 'No local');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'hybrid', 'Híbrido');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remote', 'Controlo remoto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_types', 'Tipos de trabalho.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'temporary', 'Temporário');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_location', 'Local de trabalho');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Job_title_empty', 'O cargo não pode estar vazio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_location_empty', 'Localização do trabalho não pode estar vazia');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'workplaces_empty', 'Por favor, selecione um local de trabalho');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_type_empty', 'Por favor, selecione um tipo de trabalho');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences_saved_successfully', 'Preferências de emprego foram atualizadas.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to_work', 'Aberto para o trabalho');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'see_all_details', 'Veja todos os detalhes');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences', 'Preferências de trabalho');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'set_up_services_page', 'Vamos configurar sua página de serviços');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services', 'Serviços');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_empty', 'Os serviços não podem ser vazios');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_saved_successfully', 'Serviços foram salvos.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_provided', 'Serviços prestados');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invalid_id', 'ID Inválido');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_edited_successfully', 'Serviços foram atualizados.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_job_preferences', 'Editar preferências de emprego');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences_edited_successfully', 'Preferências de emprego foram editadas.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_services', 'Carregue mais serviços');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tiers', 'Camadas');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_offer_patrons', 'Escolha o que oferecer seus patronos');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_tier', 'Adicionar camada');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_title', 'Título do nível');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_price', 'Preço de camada');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_image', 'Imagem de camada');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_description', 'Descrição da camada');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'benefits', 'Benefícios');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_without_audio_video', 'Bate-papo sem chamada de áudio e vídeo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_with_audio_without_video', 'Bate-papo com chamada de áudio e sem chamada de vídeo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_without_audio_with_video', 'Bate-papo sem chamada de áudio e com chamada de vídeo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_with_audio_video', 'Bate-papo com chamada de áudio e vídeo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat', 'Bate-papo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'live_stream', 'Transmissão ao vivo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'price_empty', 'O preço não pode estar vazio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'benefits_empty', 'Benefícios não podem ser vazios');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_chat_type', 'Por favor, selecione o tipo de bate-papo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_added_successfully', 'Camada adicionada com sucesso');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_tier', 'Editar camada');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_updated_successfully', 'Camada atualizada com sucesso');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_tier', 'Exclua sua camada');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_tier', 'Tem certeza de que deseja excluir esta camada?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patron', 'Patrono');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patrons', 'Clientes');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_you_may_know', 'Serviços que você pode gostar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to_work_posts', 'Aberto para postos de trabalho');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Afrikaans_af', 'afrikaans');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Albanian_sq', 'albanês');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Amharic_am', 'Amharic.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Arabic_ar', 'árabe');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Aragonese_an', 'Aragonês');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Armenian_hy', 'Armênio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Asturian_ast', 'Astúria');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Azerbaijani_az', 'Azerbaijani.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Basque_eu', 'Basque.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Belarusian_be', 'Bielorrusso');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bengali_bn', 'bengali');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bosnian_bs', 'Bósnio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Breton_br', 'Bretão');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bulgarian_bg', 'búlgaro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Catalan_ca', 'catalão');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Central Kurdish_ckb', 'Curdo central');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Chinese_zh', 'chinês');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Corsican_co', 'Corsicana');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Croatian_hr', 'croata');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Czech_cs', 'Checo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Danish_da', 'dinamarquês');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Dutch_nl', 'holandês');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'English_en', 'inglês');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Esperanto_eo', 'esperanto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Estonian_et', 'estoniano');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Faroese_fo', 'Faroese.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Filipino_fil', 'Filipino.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Finnish_fi', 'finlandês');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'French_fr', 'francês');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Galician_gl', 'Galego');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Georgian_ka', 'Georgiano');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'German_de', 'alemão');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Greek_el', 'grego');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Guarani_gn', 'Guarani.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Gujarati_gu', 'Gujarati.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hausa_ha', 'Hausa.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hawaiian_haw', 'havaiano');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hebrew_he', 'hebraico');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hindi_hi', 'hindi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hungarian_hu', 'húngaro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Icelandic_is', 'islandês');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Indonesian_id', 'indonésio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Interlingua_ia', 'Interlíngua');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Irish_ga', 'irlandês');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Italian_it', 'italiano');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Japanese_ja', 'japonês');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kannada_kn', 'Kannada');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kazakh_kk', 'Cazaque');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Khmer_km', 'Khmer.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Korean_ko', 'coreano');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kurdish_ku', 'curdo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kyrgyz_ky', 'Quirguistão');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lao_lo', 'Lao.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Latin_la', 'Latino');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Latvian_lv', 'letão');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lingala_ln', 'Lingala.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lithuanian_lt', 'lituano');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Macedonian_mk', 'Macedônio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Malay_ms', 'malaio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Malayalam_ml', 'Malayalam.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Maltese_mt', 'maltês');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Marathi_mr', 'Marathi.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Mongolian_mn', 'mongol');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Nepali_ne', 'Nepali.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian_no', 'norueguês');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian Bokmål_nb', 'Bokmål norueguês');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian Nynorsk_nn', 'Nynorsk norueguês');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Occitan_oc', 'Occitano');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Oriya_or', 'Oriya.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Oromo_om', 'Oromo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Pashto_ps', 'Pashto.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Persian_fa', 'persa');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Polish_pl', 'polonês');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Portuguese_pt', 'Português');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Punjabi_pa', 'Punjabi.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Quechua_qu', 'Quechua');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Romanian_ro', 'romena');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Romansh_rm', 'Romanês');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Russian_ru', 'russo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Scottish Gaelic_gd', 'gaélico escocês');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Serbian_sr', 'sérvio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Serbo_sh', 'Serbo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Shona_sn', 'Shona.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sindhi_sd', 'Sindi.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sinhala_si', 'SINHALA.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Slovak_sk', 'Eslovaco');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Slovenian_sl', 'esloveno');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Somali_so', 'Somali.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Southern Sotho_st', 'Southern Sotho.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Spanish_es', 'espanhol');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sundanese_su', 'Sundanese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Swahili_sw', 'Swahili.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Swedish_sv', 'sueco');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tajik_tg', 'Tajik.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tamil_ta', 'tâmil');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tatar_tt', 'Tártaro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Telugu_te', 'Telugu');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Thai_th', 'tailandês');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tigrinya_ti', 'Tigrya.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tongan_to', 'Tongania');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Turkish_tr', 'turco');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Turkmen_tk', 'Turkmen.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Twi_tw', 'Twi.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Ukrainian_uk', 'ucraniano');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Urdu_ur', 'urdu');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Uyghur_ug', 'Uyghur.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Uzbek_uz', 'Uzbeque');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Vietnamese_vi', 'vietnamita');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Walloon_wa', 'valão');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Welsh_cy', 'galês');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Western Frisian_fy', 'Frísia ocidental');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Xhosa_xh', 'Xhosa.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Yiddish_yi', 'Iídiche');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Yoruba_yo', 'Yoruba.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Zulu_zu', 'zulu');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_available_data', 'Não há dados disponíveis para mostrar.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'my_wallet_', 'Minha carteira');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_have_bought_products', 'Você comprou um produto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sale_products', 'Produtos de venda');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'entire_site', 'Site inteiro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'banned_for_violating', 'Você foi banido por violar nossos termos de uso. ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'were_banned_from', 'Oops, você foi banido de {site_name}');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'contact_us_more_details', 'Por favor, entre em contato conosco para mais detalhes.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pdf_file', 'Anexar arquivo PDF.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_file', 'Certificado');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_currently_working', 'Você está trabalhando atualmente?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'am_looking_to_work', 'Não, eu estou olhando para trabalhar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'am_looking_for_employees', 'Sim estou procurando funcionários');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'products_for_sale', 'Produtos para venda');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_notifications_because_you_were_banned', 'Você não pode ver suas notificações porque você foi banido');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_messages_because_you_were_banned', 'Você não pode ver suas mensagens porque foi banido');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_requests_because_you_were_banned', 'Você não pode ver seus pedidos porque você foi banido');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'withdrawal', 'Cancelamento');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'successfully_received_from', 'Dinheiro foi recebido com sucesso de');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'terms_of_use_page', '<H4> 1- Escreva seus Termos de Uso aqui. </ H4>
      ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'privacy_policy_page', '');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'about_page', '<H4> 1- Escreva sobre o seu site aqui. </ H4> Lorem Ipsum Dolor Sente-se Amet, Consectetur Adipisicing Elit, SED DO EUIUSMOD TEMPORAM INCIDURAT UT Labore et Dolore Magna Aliqua. ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_terms_page', '<H4> 1- Escreva seus Termos de Uso aqui. </ H4>
      ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'added_review_to_your_product', 'Avaliou seu produto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'coinbase', 'Coinbase.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'product_purchase', 'Compra de produtos');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sold_a_product', 'Venda do produto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'describe_your_review', 'Descreva sua revisão aqui ..');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'related_prods', 'produtos relacionados');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_open_work', 'Tem certeza de que deseja excluir?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_services', 'Tem certeza de que deseja excluir esses serviços?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_find_job_at', 'Pesquisa, encontrar e aplicar às oportunidades de emprego em');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'connect_with_friends', 'Conecte-se com os amigos!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'login_connect_friends', 'Faça o login na sua conta {Site_Name} e conecte-se com seus amigos!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remember_device', 'Lembre-se deste dispositivo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'register_create_account', 'Crie sua conta {Site_Name}!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_upgrade_to_upload', 'Apenas os usuários pro podem fazer upload, por favor, atualize para pro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'type_something_to_post', 'Seu conteúdo de postagem não pode estar vazio.');
        } else if ($value == 'russian') {
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_must_add_text_or_image_first', 'Вы должны добавить текст или изображение');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_to_cart', 'Добавить в корзину');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remove_from_cart', 'Удалить из корзины');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_from_wallet', 'Оплатить с помощью кошелька');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_top_up_wallet', 'Вам не хватает баланса для покупки, пожалуйста, пополните свой кошелек.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_to_upgrade', 'Вы собираетесь обновить до участника Pro.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_to_fund', 'Вы собираетесь пожертвовать.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'amount_can_not_empty', 'Сумма требуется');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'fund_not_found', 'Финансирование не найдено');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_successfully_done', 'Оплата успешно сделана, спасибо!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'buy_now', 'купить сейчас');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_item', 'Всего единиц товара');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_item_not_empty', 'Устройства предмета необходимы');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'currently_unavailable', 'В настоящее время недоступен.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'checkout', 'Проверить');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_items_found', 'ничего не найдено');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total', 'Всего');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'my_addresses', 'Мои адреса');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new', 'Добавить новое');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_address', 'Добавьте новый адрес');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_added', 'Ваш адрес был успешно добавлен');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_address', 'Удалить свой адрес');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_address', 'Вы уверены, что хотите удалить этот адрес?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_address', 'Редактировать адрес');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_edited', 'Ваш адрес успешно отредактирован');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_add_address', 'Пожалуйста, добавьте новый адрес');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_address', 'Пожалуйста, выберите адрес');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_alert', 'Оплата');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'purchase_the_items', 'Вы собираетесь приобрести предметы, вы хотите продолжить?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'shopping_cart', 'Корзина');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'items', 'Предметы');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'back_to_shop', 'Вернуться к магазину');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'some_products_units', 'Некоторые продукты отсутствуют на складе.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_can_not_be_empty', 'Адрес не может быть пустым');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_not_found', 'Адрес не найден');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'card_is_empty', 'Корзина пуста');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_order_has_been_placed_successfully', 'Ваш заказ был успешно размещен');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'purchased', 'Купленный');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_purchased_found', 'Не найденные предметы не найдены');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order', 'Заказ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'download_invoice', 'Скачать счет');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'id_can_not_empty', 'ID требуется');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_are_not_purchased', 'Вы еще не купили.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order_not_found', 'Не найден');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order_details', 'Информация для заказа');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'write_review', 'Написать обзор');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'request_refund', 'Запросить возврат');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_details', 'Отслеживание деталей');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delivery_address', 'Адресс доставки');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'if_the_order_status', 'Если статус заказа не был установлен для доставки в течение 60 дней с даты заказа, он будет автоматически отправлен на «доставленные».');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'if_the_order_delivered', 'Если заказ на самом деле не был доставлен, покупатель может запросить возмещение.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'placed', 'Размещены');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payments', 'Платежи');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'subtotal', 'Промежуточный итог');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sale_invoice', 'Продажа счет');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seller_name', 'Название продавца');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seller_email', 'По электронной почте продавца');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invoice_to', 'Счет-фактуру');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_details', 'Детали оплаты');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_due', 'Всего должное');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'bank_name', 'название банка');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invoice', 'Счет');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'item', 'Пункт');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'orders', 'Заказывает');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_orders_found', 'Заказы не найдены');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'products', 'Продукты');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'qty', 'Qty.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'canceled', 'Отменил');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'accepted', 'Принятый');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'packed', 'Упакованный');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'shipped', 'Отправленный');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'commission', 'Комиссия');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'final_price', 'Окончательная цена');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_number', 'Идентификационный номер');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'link', 'Ссылка на сайт');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_info_has_been_saved_successfully', 'Информация о отслеживании была успешно сохранена');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_url_can_not_be_empty', 'УРЛ отслеживания не может быть пустым');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_number_can_not_be_empty', 'Номер отслеживания не может быть пустым');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_enter_valid_url', 'Пожалуйста, введите корректный адрес');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'site_url', 'Адрес сайта');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delivered', 'Доставленный');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_explain_the_reason', 'Пожалуйста, объясните причину');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_request_is_under_review', 'Ваш запрос находится под рассмотрением, мы свяжемся с вами после выполнения.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review', 'Обзор');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'submit', 'Представлять на рассмотрение');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review_can_not_be_empty', 'Обзор содержимого требуется.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'rating_can_not_be_empty', 'Рейтинг не может быть пустым');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review_has_been_sent_successfully', 'Ваш обзор был представлен.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'admin_status_changed', 'Статус заказа был изменен');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'new_orders_has_been_placed', 'Новые заказы были размещены');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'added_tracking_info', 'Добавлена ​​информация отслеживания');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'product_approved', 'Ваш продукт был одобрен');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_product_is_under_review', 'Ваш продукт в настоящее время находится под контролем.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tweet', 'Чирикать');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask', 'Просить');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'write_answer', 'Напишите ответ и нажмите Enter');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'reply_to_answer', 'Ответить на ответ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'answered_your_question', 'ответил на ваш вопрос');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'replied_to_answer', 'ответил на ваш ответ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_question', 'понравился ваш вопрос');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_answer', 'понравился ваш ответ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'answer_mention', 'упомянул вас на ответе');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'question_mention', 'упомянул вас на вопрос');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'verified_purchase', 'Проверенная покупка');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_reviews_found', 'Отзывов не найдено');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask_anonymously', 'спросить анонимно');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask_friend', 'Спросить друга');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_for_friends', 'Поиск друзей');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'askfm_box_placeholder', 'Что, когда, почему ... спросить');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'question_can_not_empty', 'Вопрос требуется.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_who_you_want_ask', 'Пожалуйста, выберите, кто вы хотите спросить');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'asked_you_a_question', 'задал вам вопрос');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'trending_questions', 'Тенденция вопросов');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'people_liked_question', 'Людям понравился этот вопрос');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'users_liked_answer', 'Людям понравился этот ответ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_answers_found', 'Нет ответов на показать');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_header_people', 'Поиск людей и #hashtags');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'questions', 'Вопросы');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tweets', 'Твиты');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'trending_tweets', 'Трендовые твиты');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'people_liked_tweet', 'Люди понравилось этот твит');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_tweet', 'понравился твой твит');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_a_file_to_upload', 'Пожалуйста, выберите файл для загрузки');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'unlock_content_post_text', 'Разблокировать этот контент, становясь покровителем');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'join_now', 'Присоединяйся сейчас');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patreon_membership_price', 'PATREON Членство Цена');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience', 'Опыт');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_experience', 'Добавить новый опыт');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'company_name', 'Название компании');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'employment_type', 'Вид трудоустройства');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'full_time', 'На постоянной основе');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'part_time', 'Неполная занятость');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'self_employed', 'Частный предприниматель');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'freelance', 'Внештатный');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'contract', 'Договор');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'internship', 'производственная практика');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'apprenticeship', 'Ученичество');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seasonal', 'Сезонный');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'industry', 'Промышленность');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'title_empty', 'Пожалуйста, введите название');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'company_name_empty', 'Пожалуйста, введите название компании');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'employment_type_empty', 'Пожалуйста, введите тип занятости');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'location_empty', 'Пожалуйста, введите место');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'start_date_empty', 'Пожалуйста, введите дату начала');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'industry_empty', 'Пожалуйста, введите отрасль');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'description_empty', 'Пожалуйста, введите описание');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_choose_correct_experience_date', 'Пожалуйста, выберите правильную дату.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience_successfully_created', 'Опыт успешно создан.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'valid_link', 'Пожалуйста, введите правильную ссылку');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_experience', 'Удалить свой опыт');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_experience', 'Вы уверены, что хотите удалить этот опыт?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_experience', 'Опыт редактирования');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_not_owner', 'Вы не являетесь владельцем, вы можете применить это действие.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience_successfully_updated', 'Опыт успешно обновлен.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certifications', 'Сертификация');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'licenses_certifications', 'Лицензии и сертификаты');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_certification', 'Добавить новый сертификат');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issuing_organization', 'Организация выдачи');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'credential_id', 'Учетные данные ID.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'credential_url', 'УРЛЕЧЕННЫЙ УР');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issuing_organization_empty', 'Пожалуйста, введите организацию выдачи');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issue_date', 'Дата выпуска');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'expiration_date', 'Дата окончания срока');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issue_date_empty', 'Пожалуйста, введите дату выдачи.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'name_empty', 'Пожалуйста, введите имя');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_successfully_created', 'Ваш сертификат был создан.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_certification', 'Удалить свой сертификат');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_certification', 'Вы уверены, что хотите удалить этот сертификат?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_certification', 'Редактировать сертификат');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_successfully_updated', 'Ваш сертификат был обновлен.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'projects', 'Проекты');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_project', 'Добавить новый проект');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_name', 'Название проекта');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'associated_with', 'Связан с');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_url', 'URL-адрес проекта');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_successfully_added', 'Ваш проект был добавлен.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_project', 'Удалить свой проект');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_project', 'Вы уверены, что хотите удалить этот проект?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_project', 'Редактировать проект');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_successfully_updated', 'Ваш проект был обновлен.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'skills', 'Навыки и умения');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'languages', 'Языки');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to', 'Открыт кому-либо');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'finding_a_job', 'Нахождение новой работы');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'providing_services', 'Предоставление услуг');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'hiring', 'Нанимать');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_job_preferences', 'Добавить настройки работы');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tell_us_kind_work', 'Расскажите нам, какую работу вы открыты для');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'workplaces', 'Рабочие места');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_title', 'Название рабочих должностей');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'on_site', 'На месте');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'hybrid', 'Гибридный');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remote', 'Дистанционный пульт');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_types', 'Типы работы');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'temporary', 'Временный');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_location', 'Место работы');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Job_title_empty', 'Название работы не может быть пустым');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_location_empty', 'Работа расположение не может быть пустым');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'workplaces_empty', 'Пожалуйста, выберите рабочее место');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_type_empty', 'Пожалуйста, выберите тип работы');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences_saved_successfully', 'Настройки работы были обновлены.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to_work', 'Открыт для работы');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'see_all_details', 'Смотрите все детали');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences', 'Предпочтения по работе');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'set_up_services_page', 'Давайте настроим страницу своих услуг');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services', 'Услуги');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_empty', 'Услуги не могут быть пустыми');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_saved_successfully', 'Услуги были сохранены.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_provided', 'Услуги, предоставляемые');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invalid_id', 'Неверный ID');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_edited_successfully', 'Услуги были обновлены.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_job_preferences', 'Редактировать настройки задания');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences_edited_successfully', 'Предпочтения рабочих мест были отредактированы.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_services', 'Загрузить больше услуг');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tiers', 'Ящики');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_offer_patrons', 'Выберите, что предложить своим покровителям');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_tier', 'Add Tier.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_title', 'Титул уровня');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_price', 'Уровень цены');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_image', 'Образ уровня');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_description', 'Описание уровня');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'benefits', 'Преимущества');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_without_audio_video', 'Чат без аудио и видео звонка');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_with_audio_without_video', 'Чат со звуковым вызовом и без видеозвонков');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_without_audio_with_video', 'Чат без звукового вызова и с видеозвонком');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_with_audio_video', 'Чат с аудио и видеозвонком');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat', 'Чат');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'live_stream', 'Живой поток');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'price_empty', 'Цена не может быть пустой');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'benefits_empty', 'Преимущества не могут быть пустыми');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_chat_type', 'Пожалуйста, выберите тип чата');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_added_successfully', 'Успешно добавил уровень');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_tier', 'Редактировать уровень');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_updated_successfully', 'Успех Успешно обновлен');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_tier', 'Удалить свой уровень');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_tier', 'Вы уверены, что хотите удалить этот уровень?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patron', 'Покровитель');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patrons', 'Покровители');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_you_may_know', 'Услуги, которые вы можете понравиться');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to_work_posts', 'Открыть для работы посты');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Afrikaans_af', 'африкаанс');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Albanian_sq', 'албанский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Amharic_am', 'Amharic');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Arabic_ar', 'арабский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Aragonese_an', 'Арагонский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Armenian_hy', 'Армянский язык');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Asturian_ast', 'Астурский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Azerbaijani_az', 'Азербайджан');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Basque_eu', 'Баскский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Belarusian_be', 'Белорусский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bengali_bn', 'Бенгальский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bosnian_bs', 'Боснийский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Breton_br', 'Бретон');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bulgarian_bg', 'болгарский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Catalan_ca', 'Каталон');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Central Kurdish_ckb', 'Центральный курдский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Chinese_zh', 'китайский язык');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Corsican_co', 'Corsican.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Croatian_hr', 'хорватский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Czech_cs', 'чешский язык');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Danish_da', 'Датский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Dutch_nl', 'нидерландский язык');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'English_en', 'английский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Esperanto_eo', 'эсперанто');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Estonian_et', 'эстонский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Faroese_fo', 'Faroese.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Filipino_fil', 'Филиппинс');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Finnish_fi', 'Финский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'French_fr', 'Французский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Galician_gl', 'Галицкий');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Georgian_ka', 'Грузин');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'German_de', 'Немецкий');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Greek_el', 'Греческий');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Guarani_gn', 'Гуарани');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Gujarati_gu', 'Гуджарати');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hausa_ha', 'Hausa');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hawaiian_haw', 'Гавайский язык');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hebrew_he', 'иврит');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hindi_hi', 'хинди');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hungarian_hu', 'Венгерский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Icelandic_is', 'исландский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Indonesian_id', 'индонезийский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Interlingua_ia', 'Interlingua');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Irish_ga', 'Ирландский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Italian_it', 'Итальянский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Japanese_ja', 'японский язык');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kannada_kn', 'Канада');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kazakh_kk', 'Казахский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Khmer_km', 'К кхмерам');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Korean_ko', 'корейский язык');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kurdish_ku', 'Курдский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kyrgyz_ky', 'Кыргыз');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lao_lo', 'Лаос');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Latin_la', 'латинский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Latvian_lv', 'латышский язык');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lingala_ln', 'Лингла');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lithuanian_lt', 'Литовский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Macedonian_mk', 'македонский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Malay_ms', 'малайский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Malayalam_ml', 'Малаялам');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Maltese_mt', 'Мальтий');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Marathi_mr', 'Маратхи');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Mongolian_mn', 'Монгольский язык');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Nepali_ne', 'Непальский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian_no', 'Норвежский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian Bokmål_nb', 'Норвежский Бокмоль');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian Nynorsk_nn', 'Норвежский Nynorsk.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Occitan_oc', 'Заклинание');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Oriya_or', 'Ория');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Oromo_om', 'Oromo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Pashto_ps', 'Пашто');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Persian_fa', 'Персидский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Polish_pl', 'Польский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Portuguese_pt', 'португальский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Punjabi_pa', 'Пенджаби');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Quechua_qu', 'кечуа');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Romanian_ro', 'румынский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Romansh_rm', 'Romansh');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Russian_ru', 'русский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Scottish Gaelic_gd', 'Шотландский гэльс');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Serbian_sr', 'сербский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Serbo_sh', 'Сердона');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Shona_sn', 'Сюжета');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sindhi_sd', 'Sindhi.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sinhala_si', 'Синала');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Slovak_sk', 'словацкий');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Slovenian_sl', 'словенский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Somali_so', 'Сомалийский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Southern Sotho_st', 'Южный Сото');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Spanish_es', 'испанский язык');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sundanese_su', 'Sundanese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Swahili_sw', 'суахили');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Swedish_sv', 'Шведский язык');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tajik_tg', 'Таджикский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tamil_ta', 'Тамил');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tatar_tt', 'Татар');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Telugu_te', 'телугу');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Thai_th', 'Тайский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tigrinya_ti', 'Тигранья');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tongan_to', 'Тонган');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Turkish_tr', 'турецкий');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Turkmen_tk', 'туркменский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Twi_tw', 'TWI');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Ukrainian_uk', 'украинец');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Urdu_ur', 'Урду');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Uyghur_ug', 'Уйгур');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Uzbek_uz', 'Узбек');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Vietnamese_vi', 'вьетнамский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Walloon_wa', 'валлонский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Welsh_cy', 'валлийский');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Western Frisian_fy', 'Западный фриз');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Xhosa_xh', 'XHOSA');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Yiddish_yi', 'идиш');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Yoruba_yo', 'Йоруба');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Zulu_zu', 'Zulu');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_available_data', 'Нет доступных данных для отображения.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'my_wallet_', 'Мой бумажник');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_have_bought_products', 'Вы купили продукт');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sale_products', 'Продажа продуктов');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'entire_site', 'Весь сайт');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'banned_for_violating', 'Вы были запрещены на нарушение наших условий использования. ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'were_banned_from', 'Упс, вы были запрещены с {site_name}');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'contact_us_more_details', 'Пожалуйста, свяжитесь с нами, для более подробной информации.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pdf_file', 'Прикрепите PDF-файл');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_file', 'Сертификат');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_currently_working', 'Вы сейчас работаете?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'am_looking_to_work', 'Нет, я хочу работать');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'am_looking_for_employees', 'Да, я ищу сотрудников');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'products_for_sale', 'Продается продукты');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_notifications_because_you_were_banned', 'Вы не можете просматривать ваши уведомления, потому что вы были запрещены');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_messages_because_you_were_banned', 'Вы не можете просматривать ваши сообщения, потому что вы были запрещены');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_requests_because_you_were_banned', 'Вы не можете просматривать ваши запросы, потому что вы были запрещены');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'withdrawal', 'Снятие');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'successfully_received_from', 'Деньги были успешно получены от');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'terms_of_use_page', '<H4> 1- Напишите свои условия использования здесь. </ H4>
      ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'privacy_policy_page', '');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'about_page', '<H4> 1- Напишите о своем сайте здесь. </ H4> Лорема Ipsum Dolor Sit Amet, Consenttur Adipisicing Elit, SED DO EUIUSMOD DEMORE INCIDIDUTTUTT UT LABORE ET DOLORE MAGNA ALIQUA. ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_terms_page', '<H4> 1- Напишите свои условия использования здесь. </ H4>
      ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'added_review_to_your_product', 'Пересмотрел ваш продукт');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'coinbase', 'Coinbase');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'product_purchase', 'Покупка продукта');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sold_a_product', 'Продажа продукта');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'describe_your_review', 'Опишите свой обзор здесь ..');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'related_prods', 'сопутствующие товары');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_open_work', 'Вы уверены, что хотите удалить?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_services', 'Вы уверены, что хотите удалить эти услуги?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_find_job_at', 'Поиск, найти и применять к возможностям трудоустройства в');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'connect_with_friends', 'Связать с друзьями!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'login_connect_friends', 'Войдите в свою учетную запись {site_name} и подключитесь к друзьям!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remember_device', 'Помните это устройство');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'register_create_account', 'Создайте свою учетную запись {site_name}!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_upgrade_to_upload', 'Просто про пользователи могут загружать пожалуйста, обновите до Pro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'type_something_to_post', 'Ваш контент сообщения не может быть пустым.');
        } else if ($value == 'spanish') {
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_must_add_text_or_image_first', 'Debes agregar un texto o imagen.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_to_cart', 'Añadir al carrito');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remove_from_cart', 'Quitar del carrito');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_from_wallet', 'Pagar por billetera');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_top_up_wallet', 'No tiene suficiente saldo para comprar, por favor, recargue su billetera.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_to_upgrade', 'Está a punto de actualizar a un miembro profesional.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_to_fund', 'Estás a punto de donar.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'amount_can_not_empty', 'Se requiere la cantidad');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'fund_not_found', 'La financiación no se encuentra');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_successfully_done', 'Pago hecho con éxito, ¡Gracias!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'buy_now', 'Compra ahora');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_item', 'Unidades de elementos totales');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_item_not_empty', 'Se requiere unidades de elementos');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'currently_unavailable', 'Actualmente no disponible.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'checkout', 'Verificar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_items_found', 'No se encontraron artículos');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total', 'Total');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'my_addresses', 'Mis direcciones');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new', 'Añadir nuevo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_address', 'Agregar nueva dirección');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_added', 'Su dirección ha sido añadida con éxito');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_address', 'Elimina tu dirección');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_address', '¿Está seguro de que desea eliminar esta dirección?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_address', 'Editar dirección');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_edited', 'Su dirección ha sido editada con éxito');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_add_address', 'Por favor agregue una nueva dirección');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_address', 'Por favor seleccione una dirección');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_alert', 'Alerta de pago');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'purchase_the_items', 'Está a punto de comprar los artículos, ¿desea continuar?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'shopping_cart', 'Carro de compras');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'items', 'Elementos');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'back_to_shop', 'Volver a la tienda');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'some_products_units', 'Algunos productos están agotados.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_can_not_be_empty', 'La dirección no puede estar vacía');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_not_found', 'Dirección no encontrada');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'card_is_empty', 'El carrito esta vacío');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_order_has_been_placed_successfully', 'Su pedido ha sido realizado con éxito.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'purchased', 'Comprado');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_purchased_found', 'No se han encontrado artículos comprados');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order', 'Pedido');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'download_invoice', 'Descargar factura');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'id_can_not_empty', 'Se requiere identificación');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_are_not_purchased', 'Aún no has comprado.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order_not_found', 'Orden no encontrado');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order_details', 'Detalles de pedido');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'write_review', 'Escribir un comentario');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'request_refund', 'Solicitar un reembolso');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_details', 'Detalles de seguimiento');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delivery_address', 'Dirección de entrega');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'if_the_order_status', 'Si el estado del pedido no se configuró para entregar dentro de 60 días a partir de la fecha del pedido, se enviará automáticamente a \"entregado\".');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'if_the_order_delivered', 'Si el pedido no fue realmente entregado, el comprador puede solicitar un reembolso.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'placed', 'Metido');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payments', 'Pagos');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'subtotal', 'Total parcial');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sale_invoice', 'Factura de venta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seller_name', 'Nombre del vendedor');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seller_email', 'Email del vendedor');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invoice_to', 'Factura a');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_details', 'Detalles del pago');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_due', 'Total de vencimiento');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'bank_name', 'Nombre del banco');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invoice', 'Factura');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'item', 'Articulo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'orders', 'Pedidos');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_orders_found', 'No se han encontrado pedidos');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'products', 'Productos');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'qty', 'Cantidad');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'canceled', 'Cancelado');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'accepted', 'Aceptado');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'packed', 'Lleno');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'shipped', 'Enviado');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'commission', 'Comisión');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'final_price', 'Precio final');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_number', 'El número de rastreo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'link', 'Enlace');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_info_has_been_saved_successfully', 'La información de seguimiento se ha guardado correctamente');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_url_can_not_be_empty', 'La URL de seguimiento no puede estar vacía');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_number_can_not_be_empty', 'El número de seguimiento no puede estar vacío');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_enter_valid_url', 'Por favor introduzca un URL válido');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'site_url', 'Sitio URL');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delivered', 'Entregado');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_explain_the_reason', 'Por favor explique la razón');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_request_is_under_review', 'Su solicitud está bajo revisión, nos ponemos en contacto con usted una vez hecho.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review', 'Revisar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'submit', 'Entregar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review_can_not_be_empty', 'Se requiere el contenido de revisión.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'rating_can_not_be_empty', 'la calificación no puede estar vacía');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review_has_been_sent_successfully', 'Su revisión ha sido enviada.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'admin_status_changed', 'El estado del pedido ha sido cambiado');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'new_orders_has_been_placed', 'Se han colocado nuevos pedidos.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'added_tracking_info', 'Información de seguimiento añadido');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'product_approved', 'Su producto ha sido aprobado.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_product_is_under_review', 'Su producto está actualmente bajo revisión.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tweet', 'Pío');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask', 'Preguntar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'write_answer', 'Escribe una respuesta y presiona ENTER');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'reply_to_answer', 'Responder a la respuesta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'answered_your_question', 'respondió tu pregunta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'replied_to_answer', 'respondió a tu respuesta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_question', 'Me gustó tu pregunta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_answer', 'Me gustó tu respuesta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'answer_mention', 'te mencionó en una respuesta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'question_mention', 'te mencionó en una pregunta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'verified_purchase', 'Compra verificada');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_reviews_found', 'No se encontraron opiniones');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask_anonymously', 'Pregunta anónimamente');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask_friend', 'Preguntar a un amigo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_for_friends', 'Buscar amigos');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'askfm_box_placeholder', 'Qué, cuándo, por qué ... pregunte');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'question_can_not_empty', 'Se requiere la pregunta.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_who_you_want_ask', 'Por favor, seleccione a quien desea preguntar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'asked_you_a_question', 'te hizo una pregunta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'trending_questions', 'Preguntas de tendencia');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'people_liked_question', 'A la gente le gustó esta pregunta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'users_liked_answer', 'A la gente le gusta esta respuesta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_answers_found', 'No hay respuestas para mostrar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_header_people', 'Búsqueda de personas y #hashtags');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'questions', 'Preguntas');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tweets', 'Tweets');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'trending_tweets', 'Tweets de tendencia');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'people_liked_tweet', 'A la gente le gustó este tweet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_tweet', 'Me gustó su Tweet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_a_file_to_upload', 'Por favor seleccione un archivo para cargar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'unlock_content_post_text', 'Desbloquee este contenido convirtiéndose en un patrón');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'join_now', 'Únete ahora');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patreon_membership_price', 'Membresía patreon Price');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience', 'Experiencia');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_experience', 'Añadir nueva experiencia');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'company_name', 'Nombre de empresa');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'employment_type', 'Tipo de empleo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'full_time', 'Tiempo completo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'part_time', 'Medio tiempo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'self_employed', 'Trabajadores por cuenta propia');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'freelance', 'Lanza libre');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'contract', 'Contrato');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'internship', 'Internado');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'apprenticeship', 'Aprendizaje');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seasonal', 'Estacional');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'industry', 'Industria');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'title_empty', 'Por favor ingrese un título');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'company_name_empty', 'Por favor ingrese un nombre de la empresa');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'employment_type_empty', 'Por favor ingrese un tipo de empleo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'location_empty', 'Por favor ingrese una ubicación');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'start_date_empty', 'Por favor ingrese una fecha de inicio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'industry_empty', 'Por favor ingrese una industria');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'description_empty', 'Por favor ingrese una descripción');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_choose_correct_experience_date', 'Por favor, elija una fecha correcta.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience_successfully_created', 'Experiencia creada con éxito.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'valid_link', 'Por favor ingrese un enlace válido');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_experience', 'Elimina tu experiencia');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_experience', '¿Estás seguro de que quieres eliminar esta experiencia?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_experience', 'EDITAR EXPERIENCIA');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_not_owner', 'Usted no es el propietario, puede aplicar esta acción.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience_successfully_updated', 'Experimente con éxito actualizado.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certifications', 'Certificaciones');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'licenses_certifications', 'Licencias y Certificados');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_certification', 'Añadir nuevo certificado');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issuing_organization', 'Organización emisora');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'credential_id', 'ID de credencial');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'credential_url', 'URL CREDENCIAL');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issuing_organization_empty', 'Por favor ingrese una organización emisora');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issue_date', 'Fecha de asunto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'expiration_date', 'Fecha de caducidad');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issue_date_empty', 'Por favor ingrese la fecha de emisión.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'name_empty', 'Por favor ingrese un nombre');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_successfully_created', 'Su certificado ha sido creado.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_certification', 'Elimina tu certificado');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_certification', '¿Está seguro de que desea eliminar este certificado?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_certification', 'Editar certificado');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_successfully_updated', 'Su certificado ha sido actualizado.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'projects', 'Proyectos');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_project', 'Añadir nuevo proyecto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_name', 'Nombre del proyecto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'associated_with', 'Asociado con');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_url', 'URL del proyecto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_successfully_added', 'Su proyecto ha sido añadido.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_project', 'Elimina tu proyecto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_project', '¿Está seguro de que desea eliminar este proyecto?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_project', 'Editar proyecto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_successfully_updated', 'Su proyecto ha sido actualizado.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'skills', 'Habilidades');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'languages', 'Idiomas');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to', 'Abierto a');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'finding_a_job', 'Encontrar un nuevo trabajo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'providing_services', 'Prestación de servicios');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'hiring', 'Contratación');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_job_preferences', 'Añadir preferencias de trabajo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tell_us_kind_work', 'Dinos qué tipo de trabajo estás abierto a');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'workplaces', 'Lugar de trabajo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_title', 'Títulos de trabajo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'on_site', 'En el sitio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'hybrid', 'Híbrido');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remote', 'Remoto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_types', 'Tipos de trabajo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'temporary', 'Temporal');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_location', 'Locación de trabajo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Job_title_empty', 'El título del trabajo no puede estar vacío');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_location_empty', 'La ubicación del trabajo no puede estar vacía');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'workplaces_empty', 'Por favor seleccione un lugar de trabajo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_type_empty', 'Por favor seleccione un tipo de trabajo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences_saved_successfully', 'Las preferencias de trabajo se han actualizado.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to_work', 'Abierto al trabajo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'see_all_details', 'Ver todos los detalles');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences', 'Preferencias de trabajo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'set_up_services_page', 'Configuremos su página de servicios');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services', 'Servicios');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_empty', 'Los servicios no pueden estar vacíos.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_saved_successfully', 'Se han guardado los servicios.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_provided', 'Servicios prestados');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invalid_id', 'Identificación invalida');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_edited_successfully', 'Se han actualizado los servicios.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_job_preferences', 'Editar Preferencias de trabajo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences_edited_successfully', 'Se han editado las preferencias de trabajo.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_services', 'Cargar más servicios');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tiers', 'Gráficos');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_offer_patrons', 'Elige qué ofrecer a tus clientes.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_tier', 'Agregar un nivel');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_title', 'Título del nivel');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_price', 'Precio de nivel');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_image', 'Imagen de nivel');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_description', 'Descripción del nivel');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'benefits', 'Beneficios');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_without_audio_video', 'Chat sin audio y videollamada.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_with_audio_without_video', 'Chatea con llamada de audio y sin videollamada.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_without_audio_with_video', 'Chat sin llamada de audio y con videollamada.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_with_audio_video', 'Chatea con audio y videollamada.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat', 'Chat');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'live_stream', 'Corriente');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'price_empty', 'El precio no puede estar vacío');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'benefits_empty', 'Los beneficios no pueden estar vacíos');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_chat_type', 'Por favor seleccione el tipo de chat');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_added_successfully', 'Nivel agregado con éxito');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_tier', 'Editar nivel');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_updated_successfully', 'Nivel actualizado con éxito');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_tier', 'Elimina tu nivel');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_tier', '¿Estás seguro de que quieres eliminar este nivel?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patron', 'Patrón');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patrons', 'Patrones');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_you_may_know', 'Servicios que te guste');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to_work_posts', 'Abierto a publicaciones de trabajo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Afrikaans_af', 'africaans');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Albanian_sq', 'albanés');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Amharic_am', 'Amárico');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Arabic_ar', 'Arábica');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Aragonese_an', 'aragonés');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Armenian_hy', 'armenio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Asturian_ast', 'asturiano');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Azerbaijani_az', 'Azerbaiyano');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Basque_eu', 'vasco');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Belarusian_be', 'Bielorruso');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bengali_bn', 'bengalí');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bosnian_bs', 'bosnio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Breton_br', 'Bretón');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bulgarian_bg', 'búlgaro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Catalan_ca', 'catalán');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Central Kurdish_ckb', 'Kurdo central');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Chinese_zh', 'chino');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Corsican_co', 'corso');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Croatian_hr', 'croata');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Czech_cs', 'checo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Danish_da', 'danés');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Dutch_nl', 'holandés');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'English_en', 'inglés');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Esperanto_eo', 'esperanto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Estonian_et', 'Estonio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Faroese_fo', 'Feroz');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Filipino_fil', 'Filipino');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Finnish_fi', 'finlandés');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'French_fr', 'francés');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Galician_gl', 'gallego');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Georgian_ka', 'georgiano');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'German_de', 'alemán');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Greek_el', 'griego');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Guarani_gn', 'Guaraní');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Gujarati_gu', 'Gujarati');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hausa_ha', 'Hausa');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hawaiian_haw', 'hawaiano');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hebrew_he', 'hebreo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hindi_hi', 'hindi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hungarian_hu', 'húngaro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Icelandic_is', 'islandés');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Indonesian_id', 'indonesio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Interlingua_ia', 'Interlingua');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Irish_ga', 'irlandesa');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Italian_it', 'italiano');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Japanese_ja', 'japonés');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kannada_kn', 'Kannada');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kazakh_kk', 'Kazakh');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Khmer_km', 'Khmer');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Korean_ko', 'coreano');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kurdish_ku', 'kurdo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kyrgyz_ky', 'Kirguisa');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lao_lo', 'Lao');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Latin_la', 'latín');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Latvian_lv', 'letón');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lingala_ln', 'Lingala');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lithuanian_lt', 'lituano');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Macedonian_mk', 'macedónio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Malay_ms', 'malayo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Malayalam_ml', 'Malayalam');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Maltese_mt', 'maltés');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Marathi_mr', 'Marathi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Mongolian_mn', 'mongol');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Nepali_ne', 'Nepalí');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian_no', 'noruego');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian Bokmål_nb', 'Bokmål noruego');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian Nynorsk_nn', 'Noruego Nynorsk');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Occitan_oc', 'Occitano');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Oriya_or', 'Oriya');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Oromo_om', 'Oromo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Pashto_ps', 'Pashto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Persian_fa', 'persa');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Polish_pl', 'polaco');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Portuguese_pt', 'portugués');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Punjabi_pa', 'punjabi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Quechua_qu', 'quechua');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Romanian_ro', 'rumano');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Romansh_rm', 'Romansh');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Russian_ru', 'ruso');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Scottish Gaelic_gd', 'gaélico escocés');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Serbian_sr', 'serbio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Serbo_sh', 'Serbo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Shona_sn', 'Shona');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sindhi_sd', 'Sindhi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sinhala_si', 'Sinhala');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Slovak_sk', 'eslovaco');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Slovenian_sl', 'esloveno');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Somali_so', 'somalí');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Southern Sotho_st', 'Southern Sotho');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Spanish_es', 'español');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sundanese_su', 'Sundana');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Swahili_sw', 'swahili');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Swedish_sv', 'sueco');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tajik_tg', 'Tajik');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tamil_ta', 'Tamil');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tatar_tt', 'Tártaro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Telugu_te', 'Telugu');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Thai_th', 'tailandés');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tigrinya_ti', 'Tigrinosa');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tongan_to', 'Tongan');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Turkish_tr', 'turco');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Turkmen_tk', 'Turkmen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Twi_tw', 'Twi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Ukrainian_uk', 'ucranio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Urdu_ur', 'Urdu');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Uyghur_ug', 'Uyghur');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Uzbek_uz', 'Uzbeko');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Vietnamese_vi', 'vietnamita');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Walloon_wa', 'Valón');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Welsh_cy', 'galés');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Western Frisian_fy', 'Frise occidental');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Xhosa_xh', 'Xhosa');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Yiddish_yi', 'yídish');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Yoruba_yo', 'Yoruba');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Zulu_zu', 'zulú');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_available_data', 'No hay datos disponibles para mostrar.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'my_wallet_', 'Mi billetera');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_have_bought_products', 'Has comprado un producto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sale_products', 'Venta Products');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'entire_site', 'Todo el sitio');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'banned_for_violating', 'Fuiste prohibido por violar nuestros Términos de uso. ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'were_banned_from', 'Uy, fuiste prohibido desde {Site_Name}');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'contact_us_more_details', 'Por favor, póngase en contacto con nosotros para más detalles.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pdf_file', 'Adjuntar archivo PDF');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_file', 'Certificado');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_currently_working', 'Estas trabajando?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'am_looking_to_work', 'No, estoy buscando trabajar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'am_looking_for_employees', 'Si estoy buscando empleados');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'products_for_sale', 'Productos en venta');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_notifications_because_you_were_banned', 'No puedes ver tus notificaciones porque estabas prohibido');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_messages_because_you_were_banned', 'No puedes ver tus mensajes porque estabas prohibido');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_requests_because_you_were_banned', 'No puedes ver tus peticiones porque estabas prohibido');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'withdrawal', 'Retiro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'successfully_received_from', 'El dinero fue recibido con éxito de');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'terms_of_use_page', '<H4> 1- Escribe tus Términos de uso aquí. </ H4>
      ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'privacy_policy_page', '');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'about_page', '<H4> 1- Escriba sobre su sitio web aquí. </ h4> lorem ipsum dolors sitt amet, consectetur adipizing elit, sed do eiusmod temporal incididunt UT Labore et Dolore Magna Aliqua. ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_terms_page', '<H4> 1- Escribe tus Términos de uso aquí. </ H4>
      ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'added_review_to_your_product', 'revisado su producto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'coinbase', 'Coinbase');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'product_purchase', 'Compra de productos');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sold_a_product', 'Venta de productos');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'describe_your_review', 'Describe tu revisión aquí ..');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'related_prods', 'Productos relacionados');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_open_work', '¿Estas seguro que quieres borrarlo?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_services', '¿Está seguro de que desea eliminar estos servicios?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_find_job_at', 'Buscar, encontrar y solicitar oportunidades de trabajo en');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'connect_with_friends', '¡Conecta con amigos!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'login_connect_friends', 'Inicie sesión en su cuenta {Site_Name} y conecte con sus amigos!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remember_device', 'recuerda este dispositivo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'register_create_account', '¡Crea tu cuenta {Site_Name}!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_upgrade_to_upload', 'Sólo los usuarios de PRO pueden subir por favor actualizar a PRO');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'type_something_to_post', 'Su contenido de publicación no puede estar vacío.');
        } else if ($value == 'turkish') {
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_must_add_text_or_image_first', 'Bir metin veya resim eklemelisiniz');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_to_cart', 'Sepete ekle');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remove_from_cart', 'Arabadan çıkar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_from_wallet', 'Cüzdan tarafından ödeme');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_top_up_wallet', 'Satın almak için yeterli dengeniz yok, lütfen cüzdanınızı doldurun.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_to_upgrade', 'Bir Pro Üyesine yükseltmek üzeresiniz.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_to_fund', 'Bağış yapmak üzeresin.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'amount_can_not_empty', 'Tutar gereklidir');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'fund_not_found', 'Finansman bulunamadı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_successfully_done', 'Ödeme başarıyla yapıldı, teşekkür ederim!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'buy_now', 'Şimdi satın al');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_item', 'Toplam Ürün Birimleri');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_item_not_empty', 'Öğe Birimleri Gerekli');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'currently_unavailable', 'Şu anda kullanılamıyor.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'checkout', 'Ödeme');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_items_found', 'hiç bir öğe bulunamadı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total', 'Toplam');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'my_addresses', 'Adresim');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new', 'Yeni ekle');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_address', 'Yeni adres ekleyin');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_added', 'Adresiniz başarıyla eklendi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_address', 'Adresinizi Sil');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_address', 'Bu adresi silmek istediğinize emin misiniz?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_address', 'Adresi düzelt');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_edited', 'Adresiniz başarıyla düzenlendi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_add_address', 'Lütfen yeni bir adres ekle');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_address', 'Lütfen bir adres seçin');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_alert', 'Ödeme uyarısı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'purchase_the_items', 'Öğeleri satın almak üzeresiniz, devam etmek ister misiniz?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'shopping_cart', 'Alışveriş Sepeti');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'items', 'Öğeler');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'back_to_shop', 'Dükkana geri dön');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'some_products_units', 'Bazı ürünler stokta yok.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_can_not_be_empty', 'Adres boş olamaz');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_not_found', 'Adres bulunamadı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'card_is_empty', 'Kart boş');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_order_has_been_placed_successfully', 'Siparişiniz başarıyla verildi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'purchased', 'satın alındı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_purchased_found', 'Satın alınan ürün bulunamadı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order', 'Emir');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'download_invoice', 'Faturayı indirin');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'id_can_not_empty', 'Kimlik gereklidir');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_are_not_purchased', 'Henüz satın almadınız.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order_not_found', 'Sipariş bulunamadı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order_details', 'Sipariş detayları');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'write_review', 'Yorum yaz');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'request_refund', 'Geri ödeme istemek');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_details', 'Yürüyüş detayları');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delivery_address', 'Teslimat adresi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'if_the_order_status', 'Sipariş durumu, sipariş tarihinden itibaren 60 gün içinde teslim edilmemişse, otomatik olarak \"teslim\" için gönderilir.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'if_the_order_delivered', 'Sipariş aslında teslim edilmediyse, alıcı bir geri ödeme talebinde bulunabilir.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'placed', 'Yerleştirilmiş');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payments', 'Ödeme');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'subtotal', 'ara toplam');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sale_invoice', 'Satış faturası');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seller_name', 'Satıcı Adı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seller_email', 'Satıcı e-postası');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invoice_to', 'Fatura');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_details', 'Ödeme detayları');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_due', 'Tam olarak');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'bank_name', 'banka adı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invoice', 'Fatura');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'item', 'Kalem');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'orders', 'Emirler');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_orders_found', 'sipariş bulunamadı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'products', 'Ürün:% s');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'qty', 'Qty');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'canceled', 'İptal edildi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'accepted', 'Kabul edilmiş');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'packed', 'Paketlenmiş');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'shipped', 'Sevk edilen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'commission', 'komisyon');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'final_price', 'Son fiyat');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_number', 'Takip numarası');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'link', 'Bağlantı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_info_has_been_saved_successfully', 'İzleme bilgisi başarıyla kaydedildi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_url_can_not_be_empty', 'İzleme URL\'si boş olamaz');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_number_can_not_be_empty', 'İzleme numarası boş olamaz');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_enter_valid_url', 'Lütfen geçerli bir adres girin');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'site_url', 'Site URL\'si');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delivered', 'Teslim edilmiş');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_explain_the_reason', 'Lütfen nedeni açıklayın');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_request_is_under_review', 'İsteğiniz inceleme altında, bir kez yaptıktan sonra sizinle iletişim kuruyoruz.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review', 'Gözden geçirmek');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'submit', 'Göndermek');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review_can_not_be_empty', 'İnceleme içeriği gereklidir.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'rating_can_not_be_empty', 'derecelendirme boş olamaz');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review_has_been_sent_successfully', 'İncelemeniz gönderildi.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'admin_status_changed', 'Sipariş durumu değiştirildi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'new_orders_has_been_placed', 'Yeni siparişler yerleştirildi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'added_tracking_info', 'Eklenen İzleme Bilgisi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'product_approved', 'Ürününüz onaylandı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_product_is_under_review', 'Ürününüz şu anda incelenmektedir.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tweet', 'Cıvıldamak');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask', 'Sor');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'write_answer', 'Bir cevap yazın ve Enter tuşuna basın.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'reply_to_answer', 'Cevapla cevap');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'answered_your_question', 'Sorunuzu cevapladı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'replied_to_answer', 'cevabınıza cevap verdi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_question', 'Sorunu beğendi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_answer', 'Cevabını beğendim');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'answer_mention', 'bir cevaptan bahsetti');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'question_mention', 'bir sorudan bahsetti');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'verified_purchase', 'Doğrulanmış Satınalma');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_reviews_found', 'Yorum bulunamadı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask_anonymously', 'Anonim olarak sor');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask_friend', 'Arkadaşına sor');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_for_friends', 'Arkadaşlarını ara');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'askfm_box_placeholder', 'Ne, ne zaman, neden ... sor');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'question_can_not_empty', 'Soru gereklidir.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_who_you_want_ask', 'Lütfen kimi sormak istediğinizi seçin');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'asked_you_a_question', 'Sana bir soru sordu');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'trending_questions', 'Trending soruları');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'people_liked_question', 'İnsanlar bu soruyu sevdim');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'users_liked_answer', 'İnsanlar bu cevabı beğendi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_answers_found', 'Göstermede cevap yok');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_header_people', 'İnsanları arayın ve #hashtags');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'questions', 'Sorular');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tweets', 'Tweet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'trending_tweets', 'Trend Tweetler');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'people_liked_tweet', 'İnsanlar bu tweet\'i sevdim');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_tweet', 'Tweet\'ini beğendim');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_a_file_to_upload', 'Lütfen yüklenecek bir dosya seçin');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'unlock_content_post_text', 'Patron olarak bu içeriğin kilidini aç');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'join_now', 'Şimdi Katıl');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patreon_membership_price', 'Patreon üyelik Fiyat');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience', 'Tecrübe etmek');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_experience', 'Yeni Deneyim Ekle');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'company_name', 'Şirket Adı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'employment_type', 'İstihdam Tipi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'full_time', 'Tam zamanlı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'part_time', 'Yarı zamanlı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'self_employed', 'Kendi işinde çalışan');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'freelance', 'Serbest');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'contract', 'Sözleşme');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'internship', 'Staj');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'apprenticeship', 'Çıraklık');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seasonal', 'Mevsimlik');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'industry', 'Sanayi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'title_empty', 'Lütfen bir başlık girin');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'company_name_empty', 'Lütfen bir şirket adını girin');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'employment_type_empty', 'Lütfen bir iş türü girin');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'location_empty', 'Lütfen bir yer girin');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'start_date_empty', 'Lütfen bir başlangıç ​​tarihi girin');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'industry_empty', 'Lütfen bir endüstri girin');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'description_empty', 'Lütfen bir açıklama girin');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_choose_correct_experience_date', 'Lütfen doğru bir tarih seçiniz.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience_successfully_created', 'Başarıyla yaratılmış deneyim.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'valid_link', 'Lütfen geçerli bir bağlantı girin');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_experience', 'Deneyiminizi Sil');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_experience', 'Bu deneyimi silmek istediğinize emin misiniz?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_experience', 'Deneyimi düzenle');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_not_owner', 'Sen sahibi değilsin, bu işlemi uygulayabilirsiniz.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience_successfully_updated', 'Deneyim başarıyla güncellendi.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certifications', 'Sertifikalar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'licenses_certifications', 'Lisanslar ve Sertifikalar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_certification', 'Yeni sertifika ekle');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issuing_organization', 'Organizasyon Düzenleyen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'credential_id', 'Kimlik bilgisi kimliği');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'credential_url', 'Kimlik bilgisi URL\'si');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issuing_organization_empty', 'Lütfen veren bir organizasyon girin');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issue_date', 'Düzenleme tarihi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'expiration_date', 'Son kullanma tarihi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issue_date_empty', 'Lütfen ihraç tarihini girin.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'name_empty', 'Lütfen bir isim girin');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_successfully_created', 'Sertifikanız oluşturuldu.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_certification', 'Sertifikanızı Sil');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_certification', 'Bu sertifikayı silmek istediğinize emin misiniz?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_certification', 'Sertifika Düzenle');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_successfully_updated', 'Sertifikanız güncellendi.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'projects', 'Projeler');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_project', 'Yeni Proje Ekle');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_name', 'Proje Adı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'associated_with', 'İle ilişkili');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_url', 'Proje URLsi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_successfully_added', 'Projeniz eklendi.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_project', 'Projenizi silin');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_project', 'Bu projeyi silmek istediğinize emin misiniz?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_project', 'Projeyi Düzenle');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_successfully_updated', 'Projeniz güncellendi.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'skills', 'Yetenekler');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'languages', 'Diller');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to', 'Açmak');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'finding_a_job', 'Yeni bir iş bulmak');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'providing_services', 'Servis sağlama');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'hiring', 'İşe alıyor');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_job_preferences', 'İş Tercihleri ​​Ekleme');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tell_us_kind_work', 'Bize ne tür bir işin açıldığını söyle');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'workplaces', 'İşyerleri');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_title', 'İş ünvanları');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'on_site', 'Yerinde');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'hybrid', 'Hibrit');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remote', 'Uzak');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_types', 'İş türleri');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'temporary', 'Geçici');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_location', 'İş konumu');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Job_title_empty', 'İş unvanı boş olamaz');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_location_empty', 'İş yeri boş olamaz');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'workplaces_empty', 'Lütfen bir iş yeri seçiniz');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_type_empty', 'Lütfen bir iş türünü seçin');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences_saved_successfully', 'İş tercihleri ​​güncellendi.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to_work', 'İşe açık');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'see_all_details', 'Tüm detayları gör');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences', 'İş tercihleri');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'set_up_services_page', 'Hizmetlerinizi ayarlayalım');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services', 'Hizmetler');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_empty', 'Hizmetler boş olamaz');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_saved_successfully', 'Hizmetler kaydedildi.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_provided', 'Sağlanan Hizmetler');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invalid_id', 'Geçersiz kimlik');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_edited_successfully', 'Hizmetler güncellendi.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_job_preferences', 'İş Tercihlerini Düzenle');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences_edited_successfully', 'İş tercihleri ​​düzenlendi.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_services', 'Daha fazla hizmet yükleyin');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tiers', 'Katmanlar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_offer_patrons', 'Müşterilerinize ne sunacağınızı seçin');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_tier', 'Katman eklemek');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_title', 'Tier başlığı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_price', 'Katmanlı Fiyat');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_image', 'Katmanlı görüntü');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_description', 'Tier Açıklama');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'benefits', 'Faydalar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_without_audio_video', 'Ses ve görüntülü arama olmadan sohbet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_with_audio_without_video', 'Ses araması ile sohbet edin ve görüntülü arama yapmadan');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_without_audio_with_video', 'Sesli arama olmadan sohbet edin ve görüntülü görüşme');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_with_audio_video', 'Ses ve görüntülü görüşme ile sohbet et');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat', 'Sohbet etmek');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'live_stream', 'Canlı yayın');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'price_empty', 'Fiyat boş olamaz');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'benefits_empty', 'Faydaları boş olamaz');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_chat_type', 'Lütfen sohbet türünü seçin');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_added_successfully', 'Tier başarıyla eklendi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_tier', 'Tier Düzenle');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_updated_successfully', 'Tier başarıyla güncellendi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_tier', 'Seviyeni sil');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_tier', 'Bu kademeyi silmek istediğinize emin misiniz?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patron', 'Patron');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patrons', 'Patronlar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_you_may_know', 'Beğenebileceğiniz hizmetler');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to_work_posts', 'İş gönderilerine açık');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Afrikaans_af', 'AfrikaAlılar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Albanian_sq', 'Arnavut');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Amharic_am', 'Amhar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Arabic_ar', 'Arapça');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Aragonese_an', 'Aragonca');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Armenian_hy', 'Ermeni');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Asturian_ast', 'Asturyalı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Azerbaijani_az', 'Azerbaycan');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Basque_eu', 'Baskın');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Belarusian_be', 'Belarusça');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bengali_bn', 'Bengal');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bosnian_bs', 'Boşnakça');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Breton_br', 'Breton');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bulgarian_bg', 'Bulgarca');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Catalan_ca', 'Katalanca');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Central Kurdish_ckb', 'Orta Kürtçe');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Chinese_zh', 'Çince');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Corsican_co', 'Korsikalı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Croatian_hr', 'Hırvat');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Czech_cs', 'Çek');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Danish_da', 'Danimarkalı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Dutch_nl', 'Flemenkçe');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'English_en', 'ingilizce');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Esperanto_eo', 'Esperanto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Estonian_et', 'Estonyalı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Faroese_fo', 'Faroese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Filipino_fil', 'Filipo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Finnish_fi', 'Fince');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'French_fr', 'Fransızca');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Galician_gl', 'Galerici');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Georgian_ka', 'Gürcüce');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'German_de', 'Almanca');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Greek_el', 'Yunan');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Guarani_gn', 'Guarani');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Gujarati_gu', 'Gujarati');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hausa_ha', 'Hausa');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hawaiian_haw', 'Hawaii');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hebrew_he', 'İbranice');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hindi_hi', 'Hintçe');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hungarian_hu', 'Macarca');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Icelandic_is', 'İzlandaca');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Indonesian_id', 'Endonezyacı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Interlingua_ia', 'İnterlingua');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Irish_ga', 'İrlandalı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Italian_it', 'İtalyan');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Japanese_ja', 'Japonca');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kannada_kn', 'Kannada');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kazakh_kk', 'Kazak');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Khmer_km', 'Khmer');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Korean_ko', 'Koreli');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kurdish_ku', 'Kürt');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kyrgyz_ky', 'Kırgız');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lao_lo', 'Lao');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Latin_la', 'Latince');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Latvian_lv', 'Letonyalı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lingala_ln', 'Lingala');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lithuanian_lt', 'Litvanyalı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Macedonian_mk', 'Makedonyalı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Malay_ms', 'Malay');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Malayalam_ml', 'Malayalam');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Maltese_mt', 'Maltaca');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Marathi_mr', 'Marathi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Mongolian_mn', 'Moğolca');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Nepali_ne', 'Nepal');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian_no', 'Norveççe');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian Bokmål_nb', 'Norveç Bokmål');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian Nynorsk_nn', 'Norveç Nynorsk');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Occitan_oc', 'Occitan');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Oriya_or', 'Oriya');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Oromo_om', 'Orom');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Pashto_ps', 'Pölye');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Persian_fa', 'Farsça');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Polish_pl', 'Lehçe');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Portuguese_pt', 'Portekizce');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Punjabi_pa', 'Punjabi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Quechua_qu', 'Quechua');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Romanian_ro', 'Romence');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Romansh_rm', 'Romansh');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Russian_ru', 'Rusça');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Scottish Gaelic_gd', 'İskoç gaeli');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Serbian_sr', 'Sırpça');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Serbo_sh', 'Sırp');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Shona_sn', 'Shon');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sindhi_sd', 'Sindhi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sinhala_si', 'Sinhala');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Slovak_sk', 'Slovak');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Slovenian_sl', 'Slovence');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Somali_so', 'Somali');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Southern Sotho_st', 'Güney Sotho');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Spanish_es', 'İspanyol');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sundanese_su', 'Sundanese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Swahili_sw', 'Svahili');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Swedish_sv', 'İsveççe');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tajik_tg', 'Tacik');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tamil_ta', 'Tamil');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tatar_tt', 'Tatar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Telugu_te', 'Telugu');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Thai_th', 'Tayland');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tigrinya_ti', 'Tigrinya');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tongan_to', 'Tongan');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Turkish_tr', 'Türkçe');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Turkmen_tk', 'Türkmen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Twi_tw', 'Twi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Ukrainian_uk', 'Ukrayna');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Urdu_ur', 'Urdu');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Uyghur_ug', 'Uygur');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Uzbek_uz', 'Özbekçe');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Vietnamese_vi', 'Vietnam');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Walloon_wa', 'Valon');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Welsh_cy', 'Gasp');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Western Frisian_fy', 'Batı friziği');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Xhosa_xh', 'Xhosa');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Yiddish_yi', 'Yidiş');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Yoruba_yo', 'Yoruba');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Zulu_zu', 'Zulu');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_available_data', 'Gösterilecek mevcut veri yok.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'my_wallet_', 'Cüzdanım');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_have_bought_products', 'Bir ürün aldın');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sale_products', 'Satış Ürünler');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'entire_site', 'Tüm site');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'banned_for_violating', 'Kullanım şartlarımızı ihlal ettiğiniz için yasaklandınız. ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'were_banned_from', 'Oops, {sitesi_name} \'dan yasaklandınız.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'contact_us_more_details', 'Daha fazla ayrıntı için lütfen {Contact_US}.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pdf_file', 'PDF dosyasını ekle');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_file', 'Sertifika');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_currently_working', 'Şu anda çalışıyor musun?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'am_looking_to_work', 'Hayır çalışmak istiyorum');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'am_looking_for_employees', 'Evet çalışanları arıyorum');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'products_for_sale', 'Satılık ürünler');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_notifications_because_you_were_banned', 'Bildirimlerinizi göremezsiniz çünkü yasaklandınız');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_messages_because_you_were_banned', 'Mesajlarınızı görüntüleyemezsiniz çünkü sen yasaklandın');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_requests_because_you_were_banned', 'İsteklerinizi göremezsiniz, çünkü sen yasaklandın');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'withdrawal', 'Para çekme');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'successfully_received_from', 'Para başarıyla alındı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'terms_of_use_page', '<H4> 1- Burada kullanım şartlarınızı yazın. </ h4>
      ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'privacy_policy_page', '');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'about_page', '<H4> 1- Burada web sitenizi yazın. </ h4> Lorem Ipsum Dolor Sit Amet, Konsestan Adipising Elit, SED, EIUSMOD TABLOSYONU TÜRKİYETUTT UT Labore et Dolore Magna Aliqua. ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_terms_page', '<H4> 1- Burada kullanım şartlarınızı yazın. </ h4>
      ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'added_review_to_your_product', 'Ürününüzü gözden geçirdi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'coinbase', 'Sikke');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'product_purchase', 'Ürün satın alma');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sold_a_product', 'Ürün satışı');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'describe_your_review', 'Buradaki yorumunuzu açıklayın ..');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'related_prods', 'ilgili ürünler');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_open_work', 'Silmek istediğine emin misin?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_services', 'Bu hizmetleri silmek istediğinize emin misiniz?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_find_job_at', 'İş fırsatlarını arayın, bulun ve uygulayın');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'connect_with_friends', 'Arkadaşlarla bağlantı kur!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'login_connect_friends', '{Site_Name} hesabınıza giriş yapın ve arkadaşlarınızla iletişim kurun!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remember_device', 'Bu cihazı hatırla');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'register_create_account', '{Site_NAME} hesabınızı oluşturun!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_upgrade_to_upload', 'Sadece Pro Kullanıcılar lütfen Pro\'ya yükseltebilir');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'type_something_to_post', 'Gönderiniz boş olamaz.');
        } else if ($value == 'english') {
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_must_add_text_or_image_first', 'You must add a text or image');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_to_cart', 'Add To Cart');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remove_from_cart', 'Remove From Cart');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_from_wallet', 'Pay By Wallet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_top_up_wallet', 'You don\'t have enough balance to purchase, please top up your wallet.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_to_upgrade', 'You are about to upgrade to a PRO memeber.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_to_fund', 'You are about to donate.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'amount_can_not_empty', 'Amount is required');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'fund_not_found', 'Funding is not found');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_successfully_done', 'Payment successfully done, thank you!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'buy_now', 'Buy Now');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_item', 'Total Item Units');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_item_not_empty', 'Item units is required');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'currently_unavailable', 'Currently unavailable.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'checkout', 'Checkout');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_items_found', 'No items found');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total', 'Total');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'my_addresses', 'My Addresses');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new', 'Add New');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_address', 'Add New Address');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_added', 'Your address has been added successfully');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_address', 'Delete your address');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_address', 'Are you sure you want to delete this address?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_address', 'Edit Address');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_edited', 'Your address has been edited successfully');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_add_address', 'Please add a new address');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_address', 'Please select an address');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_alert', 'Payment Alert');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'purchase_the_items', 'You are about to purchase the items, do you want to proceed?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'shopping_cart', 'Shopping Cart');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'items', 'Items');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'back_to_shop', 'Back to shop');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'some_products_units', 'Some products are out of stock.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_can_not_be_empty', 'Address can not be empty');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_not_found', 'Address not found');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'card_is_empty', 'Cart is empty');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_order_has_been_placed_successfully', 'Your order has been placed successfully');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'purchased', 'Purchased');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_purchased_found', 'No purchased items found');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order', 'Order');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'download_invoice', 'Download Invoice');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'id_can_not_empty', 'ID is required');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_are_not_purchased', 'You haven\'t purchased yet.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order_not_found', 'Order not found');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order_details', 'Order details');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'write_review', 'Write Review');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'request_refund', 'Request a Refund');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_details', 'Tracking Details');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delivery_address', 'Delivery Address');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'if_the_order_status', 'If the order status wasn\'t set to delivered within 60 days from the order date, it will be automatically be sent to "Delivered".');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'if_the_order_delivered', 'If the order wasn\'t actually delivered, the buyer can request a refund.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'placed', 'Placed');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payments', 'Payments');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'subtotal', 'Subtotal');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sale_invoice', 'Sale invoice');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seller_name', 'Seller Name');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seller_email', 'Seller Email');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invoice_to', 'Invoice To');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_details', 'Payment Details');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_due', 'Total Due');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'bank_name', 'Bank name');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invoice', 'Invoice');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'item', 'Item');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'orders', 'Orders');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_orders_found', 'No orders found');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'products', 'Products');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'qty', 'Qty');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'canceled', 'Canceled');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'accepted', 'Accepted');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'packed', 'Packed');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'shipped', 'Shipped');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'commission', 'Commission');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'final_price', 'Final Price');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_number', 'Tracking Number');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'link', 'Link');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_info_has_been_saved_successfully', 'Tracking info has been saved successfully');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_url_can_not_be_empty', 'Tracking url can not be empty');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_number_can_not_be_empty', 'Tracking number can not be empty');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_enter_valid_url', 'Please enter a valid url');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'site_url', 'Site Url');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delivered', 'Delivered');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_explain_the_reason', 'Please explain the reason');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_request_is_under_review', 'Your request is under review, we contact you once done.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review', 'Review');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'submit', 'Submit');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review_can_not_be_empty', 'Review content is required.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'rating_can_not_be_empty', 'rating can not be empty');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review_has_been_sent_successfully', 'Your review has been submitted.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'admin_status_changed', 'order status has been changed');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'new_orders_has_been_placed', 'new orders has been placed');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'added_tracking_info', 'added tracking info');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'product_approved', 'your product has been approved');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_product_is_under_review', 'Your product is currently under review.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tweet', 'Tweet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask', 'Ask');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'write_answer', 'Write an answer and press enter');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'reply_to_answer', 'Reply to answer');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'answered_your_question', 'answered your question');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'replied_to_answer', 'replied to your answer');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_question', 'liked your question');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_answer', 'liked your answer');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'answer_mention', 'mentioned you on an answer');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'question_mention', 'mentioned you on a question');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'verified_purchase', 'Verified Purchase');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_reviews_found', 'No reviews found');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask_anonymously', 'Ask anonymously');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask_friend', 'Ask friend');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_for_friends', 'Search for friends');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'askfm_box_placeholder', 'What, when, why… ask');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'question_can_not_empty', 'Question is required.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_who_you_want_ask', 'Please select who you want to ask');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'asked_you_a_question', 'asked you a question');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'trending_questions', 'Trending Questions');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'people_liked_question', 'People liked this question');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'users_liked_answer', 'People liked this answer');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_answers_found', 'No answers to show');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_header_people', 'Search for people and #hashtags');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'questions', 'Questions');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tweets', 'Tweets');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'trending_tweets', 'Trending Tweets');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'people_liked_tweet', 'People liked this tweet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_tweet', 'liked your tweet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_a_file_to_upload', 'Please select a file to upload');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'unlock_content_post_text', 'Unlock this content by becoming a patron');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'join_now', 'Join now');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patreon_membership_price', 'Patreon membership price');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience', 'Experience');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_experience', 'Add New Experience');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'company_name', 'Company name');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'employment_type', 'Employment type');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'full_time', 'Full time');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'part_time', 'Part time');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'self_employed', 'Self employed');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'freelance', 'Freelance');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'contract', 'Contract');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'internship', 'Internship');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'apprenticeship', 'Apprenticeship');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seasonal', 'Seasonal');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'industry', 'Industry');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'title_empty', 'Please enter a title');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'company_name_empty', 'Please enter a company name');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'employment_type_empty', 'Please enter a employment type');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'location_empty', 'Please enter a location');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'start_date_empty', 'Please enter a start date');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'industry_empty', 'Please enter an industry');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'description_empty', 'Please enter a description');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_choose_correct_experience_date', 'Please choose a correct date.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience_successfully_created', 'Experience successfully created.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'valid_link', 'Please enter a valid link');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_experience', 'Delete your experience');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_experience', 'Are you sure you want to delete this experience?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_experience', 'Edit experience');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_not_owner', 'You are not the owner, you can apply this action.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience_successfully_updated', 'Experience successfully updated.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certifications', 'Certifications');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'licenses_certifications', 'Licenses & Certificates');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_certification', 'Add New Certificate');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issuing_organization', 'Issuing organization');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'credential_id', 'Credential ID');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'credential_url', 'Credential URL');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issuing_organization_empty', 'Please enter an issuing organization');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issue_date', 'Issue date');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'expiration_date', 'Expiration date');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issue_date_empty', 'Please enter the issuing date.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'name_empty', 'Please enter a name');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_successfully_created', 'Your certificate has been created.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_certification', 'Delete your certificate');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_certification', 'Are you sure you want to delete this certificate?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_certification', 'Edit Certificate');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_successfully_updated', 'Your certificate has been updated.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'projects', 'Projects');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_project', 'Add new project');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_name', 'Project name');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'associated_with', 'Associated with');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_url', 'Project URL');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_successfully_added', 'Your project has been added.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_project', 'Delete your project');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_project', 'Are you sure you want to delete this project?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_project', 'Edit Project');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_successfully_updated', 'Your project has been updated.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'skills', 'Skills');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'languages', 'Languages');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to', 'Open To');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'finding_a_job', 'Finding a new job');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'providing_services', 'Providing services');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'hiring', 'Hiring');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_job_preferences', 'Add job preferences');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tell_us_kind_work', 'Tell us what kind of work you’re open to');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'workplaces', 'Workplaces');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_title', 'Job titles');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'on_site', 'On site');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'hybrid', 'Hybrid');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remote', 'Remote');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_types', 'Job types');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'temporary', 'Temporary');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_location', 'Job location');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Job_title_empty', 'Job title can not be empty');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_location_empty', 'Job location can not be empty');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'workplaces_empty', 'Please select a workplace');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_type_empty', 'Please select a job type');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences_saved_successfully', 'Job preferences have been updated.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to_work', 'Open to work');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'see_all_details', 'See all details');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences', 'Job preferences');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'set_up_services_page', 'Let’s set up your services page');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services', 'Services');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_empty', 'Services can not be empty');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_saved_successfully', 'Services have been saved.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_provided', 'Services provided');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invalid_id', 'Invalid id');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_edited_successfully', 'Services have been updated.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_job_preferences', 'Edit job preferences');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences_edited_successfully', 'Job preferences have been edited.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_services', 'Load more services');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tiers', 'Tiers');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_offer_patrons', 'Choose what to offer your patrons');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_tier', 'Add tier');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_title', 'Tier title');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_price', 'Tier price');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_image', 'Tier image');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_description', 'Tier description');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'benefits', 'Benefits');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_without_audio_video', 'Chat without audio and video call');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_with_audio_without_video', 'Chat with audio call and without video call');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_without_audio_with_video', 'Chat without audio call and with video call');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_with_audio_video', 'Chat with audio and video call');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat', 'Chat');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'live_stream', 'Live Stream');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'price_empty', 'Price can not be empty');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'benefits_empty', 'Benefits can not be empty');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_chat_type', 'Please select the chat type');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_added_successfully', 'Tier successfully added');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_tier', 'Edit tier');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_updated_successfully', 'Tier successfully updated');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_tier', 'Delete your tier');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_tier', 'Are you sure you want to delete this tier?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patron', 'Patron');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patrons', 'Patrons');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_you_may_know', 'Services you may like');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to_work_posts', 'Open to work posts');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Afrikaans_af', 'Afrikaans');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Albanian_sq', 'Albanian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Amharic_am', 'Amharic');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Arabic_ar', 'Arabic');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Aragonese_an', 'Aragonese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Armenian_hy', 'Armenian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Asturian_ast', 'Asturian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Azerbaijani_az', 'Azerbaijani');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Basque_eu', 'Basque');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Belarusian_be', 'Belarusian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bengali_bn', 'Bengali');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bosnian_bs', 'Bosnian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Breton_br', 'Breton');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bulgarian_bg', 'Bulgarian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Catalan_ca', 'Catalan');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Central Kurdish_ckb', 'Central Kurdish');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Chinese_zh', 'Chinese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Corsican_co', 'Corsican');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Croatian_hr', 'Croatian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Czech_cs', 'Czech');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Danish_da', 'Danish');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Dutch_nl', 'Dutch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'English_en', 'English');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Esperanto_eo', 'Esperanto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Estonian_et', 'Estonian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Faroese_fo', 'Faroese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Filipino_fil', 'Filipino');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Finnish_fi', 'Finnish');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'French_fr', 'French');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Galician_gl', 'Galician');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Georgian_ka', 'Georgian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'German_de', 'German');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Greek_el', 'Greek');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Guarani_gn', 'Guarani');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Gujarati_gu', 'Gujarati');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hausa_ha', 'Hausa');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hawaiian_haw', 'Hawaiian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hebrew_he', 'Hebrew');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hindi_hi', 'Hindi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hungarian_hu', 'Hungarian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Icelandic_is', 'Icelandic');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Indonesian_id', 'Indonesian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Interlingua_ia', 'Interlingua');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Irish_ga', 'Irish');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Italian_it', 'Italian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Japanese_ja', 'Japanese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kannada_kn', 'Kannada');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kazakh_kk', 'Kazakh');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Khmer_km', 'Khmer');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Korean_ko', 'Korean');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kurdish_ku', 'Kurdish');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kyrgyz_ky', 'Kyrgyz');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lao_lo', 'Lao');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Latin_la', 'Latin');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Latvian_lv', 'Latvian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lingala_ln', 'Lingala');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lithuanian_lt', 'Lithuanian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Macedonian_mk', 'Macedonian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Malay_ms', 'Malay');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Malayalam_ml', 'Malayalam');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Maltese_mt', 'Maltese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Marathi_mr', 'Marathi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Mongolian_mn', 'Mongolian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Nepali_ne', 'Nepali');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian_no', 'Norwegian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian Bokmål_nb', 'Norwegian Bokmål');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian Nynorsk_nn', 'Norwegian Nynorsk');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Occitan_oc', 'Occitan');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Oriya_or', 'Oriya');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Oromo_om', 'Oromo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Pashto_ps', 'Pashto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Persian_fa', 'Persian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Polish_pl', 'Polish');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Portuguese_pt', 'Portuguese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Punjabi_pa', 'Punjabi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Quechua_qu', 'Quechua');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Romanian_ro', 'Romanian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Romansh_rm', 'Romansh');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Russian_ru', 'Russian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Scottish Gaelic_gd', 'Scottish Gaelic');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Serbian_sr', 'Serbian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Serbo_sh', 'Serbo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Shona_sn', 'Shona');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sindhi_sd', 'Sindhi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sinhala_si', 'Sinhala');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Slovak_sk', 'Slovak');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Slovenian_sl', 'Slovenian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Somali_so', 'Somali');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Southern Sotho_st', 'Southern Sotho');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Spanish_es', 'Spanish');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sundanese_su', 'Sundanese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Swahili_sw', 'Swahili');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Swedish_sv', 'Swedish');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tajik_tg', 'Tajik');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tamil_ta', 'Tamil');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tatar_tt', 'Tatar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Telugu_te', 'Telugu');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Thai_th', 'Thai');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tigrinya_ti', 'Tigrinya');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tongan_to', 'Tongan');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Turkish_tr', 'Turkish');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Turkmen_tk', 'Turkmen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Twi_tw', 'Twi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Ukrainian_uk', 'Ukrainian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Urdu_ur', 'Urdu');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Uyghur_ug', 'Uyghur');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Uzbek_uz', 'Uzbek');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Vietnamese_vi', 'Vietnamese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Walloon_wa', 'Walloon');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Welsh_cy', 'Welsh');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Western Frisian_fy', 'Western Frisian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Xhosa_xh', 'Xhosa');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Yiddish_yi', 'Yiddish');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Yoruba_yo', 'Yoruba');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Zulu_zu', 'Zulu');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_available_data', 'No available data to show.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'my_wallet_', 'My Wallet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_have_bought_products', 'You have bought a product');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sale_products', 'Sale products');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'entire_site', 'Entire Site');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'banned_for_violating', 'You were banned for violating our terms of use. Please {contact_us} for more details.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'were_banned_from', 'Oops, You were banned from {site_name}');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'contact_us_more_details', 'Please {contact_us} for more details.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pdf_file', 'Attach PDF File');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_file', 'Certificate');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_currently_working', 'Are you currently working?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'am_looking_to_work', 'No I am looking to work');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'am_looking_for_employees', 'Yes I am looking for employees');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'products_for_sale', 'Products for sale');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_notifications_because_you_were_banned', 'You can\'t view your notifications because you were banned');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_messages_because_you_were_banned', 'You can\'t view your messages because you were banned');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_requests_because_you_were_banned', 'You can\'t view your requests because you were banned');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'withdrawal', 'Withdrawal');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'successfully_received_from', 'Money was successfully received from');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'terms_of_use_page', '<h4>1- Write your Terms Of Use here.</h4>
      Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod          tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,          quis sdnostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo          consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse          cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non          proident, sunt in culpa qui officia deserunt mollit anim id est laborum.          <br><br>          <h4>2- Random title</h4>          Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod          tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,          quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo          consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse          cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non          proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'privacy_policy_page', '<h2>Who we are?</h2>
      Provide name and contact details of the data controller. This will typically be your business or you, if you are a sole trader. Where applicable, you should include the identity and contact details of the controller’s representative and/or the data protection officer.

      <h2>What information do we collect?</h2>
      Specify the types of personal information you collect, eg names, addresses, user names, etc. You should include specific details on:
      how you collect data (eg when a user registers, purchases or uses your services, completes a contact form, signs up to a newsletter, etc)
      what specific data you collect through each of the data collection method
      if you collect data from third parties, you must specify categories of data and source
      if you process sensitive personal data or financial information, and how you handle this
      <br><br>
      You may want to provide the user with relevant definitions in relation to personal data and sensitive personal data.
      <br><br>
      <h2>How do we use personal information?</h2>
      Describe in detail all the service- and business-related purposes for which you will process data. For example, this may include things like:
      personalisation of content, business information or user experience
      account set up and administration
      delivering marketing and events communication
      carrying out polls and surveys
      internal research and development purposes
      providing goods and services
      legal obligations (eg prevention of fraud)
      meeting internal audit requirements
      <br><br>
      Please note this list is not exhaustive. You will need to record all purposes for which you process personal data.
      <br><br>
      <h2>What legal basis do we have for processing your personal data?</h2>
      Describe the relevant processing conditions contained within the GDPR. There are six possible legal grounds:
      consent
      contract
      legitimate interests
      vital interests
      public task
      legal obligation
      <br><br>
      Provide detailed information on all grounds that apply to your processing, and why. If you rely on consent, explain how individuals can withdraw and manage their consent. If you rely on legitimate interests, explain clearly what these are.
      <br><br>
      If you’re processing special category personal data, you will have to satisfy at least one of the six processing conditions, as well as additional requirements for processing under the GDPR. Provide information on all additional grounds that apply.
      <br><br>
      <h2>When do we share personal data?</h2>
      Explain that you will treat personal data confidentially and describe the circumstances when you might disclose or share it. Eg, when necessary to provide your services or conduct your business operations, as outlined in your purposes for processing. You should provide information on:
      how you will share the data
      what safeguards you will have in place
      what parties you may share the data with and why

      <h2>Where do we store and process personal data?</h2>
      If applicable, explain if you intend to store and process data outside of the data subject’s home country. Outline the steps you will take to ensure the data is processed according to your privacy policy and the applicable law of the country where data is located.

      If you transfer data outside the European Economic Area, outline the measures you will put in place to provide an appropriate level of data privacy protection. Eg contractual clauses, data transfer agreements, etc.

      <h2>How do we secure personal data?</h2>
      Describe your approach to data security and the technologies and procedures you use to protect personal information. For example, these may be measures:
      to protect data against accidental loss
      to prevent unauthorised access, use, destruction or disclosure
      to ensure business continuity and disaster recovery
      to restrict access to personal information
      to conduct privacy impact assessments in accordance with the law and your business policies
      to train staff and contractors on data security
      to manage third party risks, through use of contracts and security reviews
      <br><br>
      Please note this list is not exhaustive. You should record all mechanisms you rely on to protect personal data. You should also state if your organisation adheres to certain accepted standards or regulatory requirements.
      <br><br>
      <h2>How long do we keep your personal data for?</h2>
      Provide specific information on the length of time you will keep the information for in relation to each processing purpose. The GDPR requires you to retain data for no longer than reasonably necessary. Include details of your data or records retention schedules, or link to additional resources where these are published.
      <br><br>
      If you cannot state a specific period, you need to set out the criteria you will apply to determine how long to keep the data for (eg local laws, contractual obligations, etc)
      <br><br>
      You should also outline how you securely dispose of data after you no longer need it.
      <br><br>
      <h2>Your rights in relation to personal data</h2>
      Under the GDPR, you must respect the right of data subjects to access and control their personal data. In your privacy notice, you must outline their rights in respect of:
      access to personal information
      correction and deletion
      withdrawal of consent (if processing data on condition of consent)
      data portability
      restriction of processing and objection
      lodging a complaint with the Information Commissioner’s Office

      You should explain how individuals can exercise their rights, and how you plan to respond to subject data requests. State if any relevant exemptions may apply and set out any identity verifications procedures you may rely on.

      Include details of the circumstances where data subject rights may be limited, eg if fulfilling the data subject request may expose personal data about another person, or if you’re asked to delete data which you are required to keep by law.

      <h2>Use of automated decision-making and profiling</h2>
      Where you use profiling or other automated decision-making, you must disclose this in your privacy policy. In such cases, you must provide details on existence of any automated decision-making, together with information about the logic involved, and the likely significance and consequences of the processing of the individual.

      <h2>How to contact us?</h2>
      Explain how data subject can get in touch if they have questions or concerns about your privacy practices, their personal information, or if they wish to file a complaint. Describe all ways in which they can contact you – eg online, by email or postal mail.
      <br><br>
      If applicable, you may also include information on:
      <br><br>
      <h2>Use of cookies and other technologies</h2>
      You may include a link to further information, or describe within the policy if you intend to set and use cookies, tracking and similar technologies to store and manage user preferences on your website, advertise, enable content or otherwise analyse user and usage data. Provide information on what types of cookies and technologies you use, why you use them and how an individual can control and manage them.
      <br><br>
      Linking to other websites / third party content
      If you link to external sites and resources from your website, be specific on whether this constitutes endorsement, and if you take any responsibility for the content (or information contained within) any linked website.
      <br><br>
      You may wish to consider adding other optional clauses to your privacy policy, depending on your business’ circumstances.
      ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'about_page', '<h4>1- Write about your website here.</h4>          Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod          tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,          quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo          consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse          cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non          proident, sunt in culpa qui officia deserunt mollit anim id est laborum.          <br><br>          <h4>2- Random title</h4>          Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod          tempor incididunt ut labore et dxzcolore magna aliqua. Ut enim ad minim veniam,          quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo          consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse          cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non          proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_terms_page', '<h4>1- Write your Terms Of Use here.</h4>
      Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod          tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,          quis sdnostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo          consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse          cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non          proident, sunt in culpa qui officia deserunt mollit anim id est laborum.          <br><br>          <h4>2- Random title</h4>          Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod          tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,          quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo          consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse          cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non          proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'added_review_to_your_product', 'reviewed your product');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'coinbase', 'Coinbase');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'product_purchase', 'Product Purchase');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sold_a_product', 'Product Sale');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'describe_your_review', 'Describe your review here..');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'related_prods', 'Related Products');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_open_work', 'Are you sure you want to delete?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_services', 'Are you sure you want to delete these services?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_find_job_at', 'Search, find and apply to job opportunities at');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'connect_with_friends', 'Connect with friends!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'login_connect_friends', 'Login into your {site_name} account and connect with your friends!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remember_device', 'Remember this device');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'register_create_account', 'Create your {site_name} Account!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_upgrade_to_upload', 'Just Pro users can upload Please upgrade to pro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'type_something_to_post', 'Your post content can\'t be empty.');
        } else if ($value != 'english') {
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_must_add_text_or_image_first', 'You must add a text or image');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_to_cart', 'Add To Cart');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remove_from_cart', 'Remove From Cart');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_from_wallet', 'Pay By Wallet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_top_up_wallet', 'You don\'t have enough balance to purchase, please top up your wallet.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_to_upgrade', 'You are about to upgrade to a PRO memeber.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_to_fund', 'You are about to donate.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'amount_can_not_empty', 'Amount is required');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'fund_not_found', 'Funding is not found');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_successfully_done', 'Payment successfully done, thank you!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'buy_now', 'Buy Now');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_item', 'Total Item Units');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_item_not_empty', 'Item units is required');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'currently_unavailable', 'Currently unavailable.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'checkout', 'Checkout');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_items_found', 'No items found');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total', 'Total');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'my_addresses', 'My Addresses');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new', 'Add New');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_address', 'Add New Address');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_added', 'Your address has been added successfully');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_address', 'Delete your address');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_address', 'Are you sure you want to delete this address?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_address', 'Edit Address');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_edited', 'Your address has been edited successfully');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_add_address', 'Please add a new address');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_address', 'Please select an address');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_alert', 'Payment Alert');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'purchase_the_items', 'You are about to purchase the items, do you want to proceed?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'shopping_cart', 'Shopping Cart');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'items', 'Items');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'back_to_shop', 'Back to shop');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'some_products_units', 'Some products are out of stock.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_can_not_be_empty', 'Address can not be empty');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'address_not_found', 'Address not found');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'card_is_empty', 'Cart is empty');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_order_has_been_placed_successfully', 'Your order has been placed successfully');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'purchased', 'Purchased');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_purchased_found', 'No purchased items found');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order', 'Order');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'download_invoice', 'Download Invoice');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'id_can_not_empty', 'ID is required');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_are_not_purchased', 'You haven\'t purchased yet.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order_not_found', 'Order not found');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'order_details', 'Order details');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'write_review', 'Write Review');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'request_refund', 'Request a Refund');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_details', 'Tracking Details');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delivery_address', 'Delivery Address');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'if_the_order_status', 'If the order status wasn\'t set to delivered within 60 days from the order date, it will be automatically be sent to "Delivered".');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'if_the_order_delivered', 'If the order wasn\'t actually delivered, the buyer can request a refund.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'placed', 'Placed');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payments', 'Payments');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'subtotal', 'Subtotal');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sale_invoice', 'Sale invoice');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seller_name', 'Seller Name');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seller_email', 'Seller Email');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invoice_to', 'Invoice To');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'payment_details', 'Payment Details');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'total_due', 'Total Due');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'bank_name', 'Bank name');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invoice', 'Invoice');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'item', 'Item');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'orders', 'Orders');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_orders_found', 'No orders found');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'products', 'Products');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'qty', 'Qty');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'canceled', 'Canceled');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'accepted', 'Accepted');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'packed', 'Packed');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'shipped', 'Shipped');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'commission', 'Commission');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'final_price', 'Final Price');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_number', 'Tracking Number');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'link', 'Link');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_info_has_been_saved_successfully', 'Tracking info has been saved successfully');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_url_can_not_be_empty', 'Tracking url can not be empty');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tracking_number_can_not_be_empty', 'Tracking number can not be empty');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_enter_valid_url', 'Please enter a valid url');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'site_url', 'Site Url');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delivered', 'Delivered');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_explain_the_reason', 'Please explain the reason');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_request_is_under_review', 'Your request is under review, we contact you once done.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review', 'Review');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'submit', 'Submit');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review_can_not_be_empty', 'Review content is required.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'rating_can_not_be_empty', 'rating can not be empty');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'review_has_been_sent_successfully', 'Your review has been submitted.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'admin_status_changed', 'order status has been changed');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'new_orders_has_been_placed', 'new orders has been placed');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'added_tracking_info', 'added tracking info');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'product_approved', 'your product has been approved');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_product_is_under_review', 'Your product is currently under review.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tweet', 'Tweet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask', 'Ask');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'write_answer', 'Write an answer and press enter');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'reply_to_answer', 'Reply to answer');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'answered_your_question', 'answered your question');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'replied_to_answer', 'replied to your answer');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_question', 'liked your question');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_answer', 'liked your answer');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'answer_mention', 'mentioned you on an answer');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'question_mention', 'mentioned you on a question');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'verified_purchase', 'Verified Purchase');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_reviews_found', 'No reviews found');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask_anonymously', 'Ask anonymously');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'ask_friend', 'Ask friend');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_for_friends', 'Search for friends');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'askfm_box_placeholder', 'What, when, why… ask');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'question_can_not_empty', 'Question is required.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_who_you_want_ask', 'Please select who you want to ask');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'asked_you_a_question', 'asked you a question');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'trending_questions', 'Trending Questions');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'people_liked_question', 'People liked this question');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'users_liked_answer', 'People liked this answer');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_answers_found', 'No answers to show');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_header_people', 'Search for people and #hashtags');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'questions', 'Questions');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tweets', 'Tweets');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'trending_tweets', 'Trending Tweets');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'people_liked_tweet', 'People liked this tweet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'liked_tweet', 'liked your tweet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_a_file_to_upload', 'Please select a file to upload');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'unlock_content_post_text', 'Unlock this content by becoming a patron');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'join_now', 'Join now');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patreon_membership_price', 'Patreon membership price');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience', 'Experience');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_experience', 'Add New Experience');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'company_name', 'Company name');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'employment_type', 'Employment type');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'full_time', 'Full time');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'part_time', 'Part time');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'self_employed', 'Self employed');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'freelance', 'Freelance');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'contract', 'Contract');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'internship', 'Internship');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'apprenticeship', 'Apprenticeship');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'seasonal', 'Seasonal');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'industry', 'Industry');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'title_empty', 'Please enter a title');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'company_name_empty', 'Please enter a company name');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'employment_type_empty', 'Please enter a employment type');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'location_empty', 'Please enter a location');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'start_date_empty', 'Please enter a start date');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'industry_empty', 'Please enter an industry');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'description_empty', 'Please enter a description');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_choose_correct_experience_date', 'Please choose a correct date.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience_successfully_created', 'Experience successfully created.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'valid_link', 'Please enter a valid link');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_experience', 'Delete your experience');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_experience', 'Are you sure you want to delete this experience?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_experience', 'Edit experience');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_not_owner', 'You are not the owner, you can apply this action.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'experience_successfully_updated', 'Experience successfully updated.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certifications', 'Certifications');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'licenses_certifications', 'Licenses & Certificates');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_certification', 'Add New Certificate');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issuing_organization', 'Issuing organization');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'credential_id', 'Credential ID');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'credential_url', 'Credential URL');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issuing_organization_empty', 'Please enter an issuing organization');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issue_date', 'Issue date');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'expiration_date', 'Expiration date');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'issue_date_empty', 'Please enter the issuing date.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'name_empty', 'Please enter a name');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_successfully_created', 'Your certificate has been created.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_certification', 'Delete your certificate');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_certification', 'Are you sure you want to delete this certificate?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_certification', 'Edit Certificate');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_successfully_updated', 'Your certificate has been updated.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'projects', 'Projects');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_new_project', 'Add new project');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_name', 'Project name');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'associated_with', 'Associated with');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_url', 'Project URL');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_successfully_added', 'Your project has been added.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_project', 'Delete your project');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_project', 'Are you sure you want to delete this project?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_project', 'Edit Project');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'project_successfully_updated', 'Your project has been updated.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'skills', 'Skills');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'languages', 'Languages');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to', 'Open To');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'finding_a_job', 'Finding a new job');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'providing_services', 'Providing services');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'hiring', 'Hiring');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_job_preferences', 'Add job preferences');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tell_us_kind_work', 'Tell us what kind of work you’re open to');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'workplaces', 'Workplaces');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_title', 'Job titles');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'on_site', 'On site');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'hybrid', 'Hybrid');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remote', 'Remote');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_types', 'Job types');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'temporary', 'Temporary');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_location', 'Job location');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Job_title_empty', 'Job title can not be empty');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_location_empty', 'Job location can not be empty');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'workplaces_empty', 'Please select a workplace');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_type_empty', 'Please select a job type');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences_saved_successfully', 'Job preferences have been updated.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to_work', 'Open to work');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'see_all_details', 'See all details');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences', 'Job preferences');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'set_up_services_page', 'Let’s set up your services page');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services', 'Services');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_empty', 'Services can not be empty');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_saved_successfully', 'Services have been saved.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_provided', 'Services provided');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'invalid_id', 'Invalid id');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_edited_successfully', 'Services have been updated.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_job_preferences', 'Edit job preferences');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'job_preferences_edited_successfully', 'Job preferences have been edited.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_services', 'Load more services');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tiers', 'Tiers');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_offer_patrons', 'Choose what to offer your patrons');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'add_tier', 'Add tier');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_title', 'Tier title');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_price', 'Tier price');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_image', 'Tier image');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_description', 'Tier description');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'benefits', 'Benefits');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_without_audio_video', 'Chat without audio and video call');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_with_audio_without_video', 'Chat with audio call and without video call');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_without_audio_with_video', 'Chat without audio call and with video call');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat_with_audio_video', 'Chat with audio and video call');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'chat', 'Chat');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'live_stream', 'Live Stream');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'price_empty', 'Price can not be empty');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'benefits_empty', 'Benefits can not be empty');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_select_chat_type', 'Please select the chat type');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_added_successfully', 'Tier successfully added');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_tier', 'Edit tier');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'tier_updated_successfully', 'Tier successfully updated');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_your_tier', 'Delete your tier');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_your_tier', 'Are you sure you want to delete this tier?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patron', 'Patron');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'patrons', 'Patrons');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'services_you_may_know', 'Services you may like');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'open_to_work_posts', 'Open to work posts');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Afrikaans_af', 'Afrikaans');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Albanian_sq', 'Albanian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Amharic_am', 'Amharic');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Arabic_ar', 'Arabic');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Aragonese_an', 'Aragonese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Armenian_hy', 'Armenian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Asturian_ast', 'Asturian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Azerbaijani_az', 'Azerbaijani');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Basque_eu', 'Basque');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Belarusian_be', 'Belarusian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bengali_bn', 'Bengali');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bosnian_bs', 'Bosnian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Breton_br', 'Breton');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Bulgarian_bg', 'Bulgarian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Catalan_ca', 'Catalan');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Central Kurdish_ckb', 'Central Kurdish');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Chinese_zh', 'Chinese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Corsican_co', 'Corsican');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Croatian_hr', 'Croatian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Czech_cs', 'Czech');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Danish_da', 'Danish');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Dutch_nl', 'Dutch');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'English_en', 'English');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Esperanto_eo', 'Esperanto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Estonian_et', 'Estonian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Faroese_fo', 'Faroese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Filipino_fil', 'Filipino');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Finnish_fi', 'Finnish');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'French_fr', 'French');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Galician_gl', 'Galician');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Georgian_ka', 'Georgian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'German_de', 'German');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Greek_el', 'Greek');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Guarani_gn', 'Guarani');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Gujarati_gu', 'Gujarati');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hausa_ha', 'Hausa');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hawaiian_haw', 'Hawaiian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hebrew_he', 'Hebrew');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hindi_hi', 'Hindi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Hungarian_hu', 'Hungarian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Icelandic_is', 'Icelandic');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Indonesian_id', 'Indonesian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Interlingua_ia', 'Interlingua');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Irish_ga', 'Irish');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Italian_it', 'Italian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Japanese_ja', 'Japanese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kannada_kn', 'Kannada');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kazakh_kk', 'Kazakh');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Khmer_km', 'Khmer');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Korean_ko', 'Korean');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kurdish_ku', 'Kurdish');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Kyrgyz_ky', 'Kyrgyz');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lao_lo', 'Lao');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Latin_la', 'Latin');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Latvian_lv', 'Latvian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lingala_ln', 'Lingala');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Lithuanian_lt', 'Lithuanian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Macedonian_mk', 'Macedonian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Malay_ms', 'Malay');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Malayalam_ml', 'Malayalam');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Maltese_mt', 'Maltese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Marathi_mr', 'Marathi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Mongolian_mn', 'Mongolian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Nepali_ne', 'Nepali');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian_no', 'Norwegian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian Bokmål_nb', 'Norwegian Bokmål');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Norwegian Nynorsk_nn', 'Norwegian Nynorsk');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Occitan_oc', 'Occitan');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Oriya_or', 'Oriya');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Oromo_om', 'Oromo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Pashto_ps', 'Pashto');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Persian_fa', 'Persian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Polish_pl', 'Polish');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Portuguese_pt', 'Portuguese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Punjabi_pa', 'Punjabi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Quechua_qu', 'Quechua');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Romanian_ro', 'Romanian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Romansh_rm', 'Romansh');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Russian_ru', 'Russian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Scottish Gaelic_gd', 'Scottish Gaelic');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Serbian_sr', 'Serbian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Serbo_sh', 'Serbo');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Shona_sn', 'Shona');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sindhi_sd', 'Sindhi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sinhala_si', 'Sinhala');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Slovak_sk', 'Slovak');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Slovenian_sl', 'Slovenian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Somali_so', 'Somali');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Southern Sotho_st', 'Southern Sotho');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Spanish_es', 'Spanish');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Sundanese_su', 'Sundanese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Swahili_sw', 'Swahili');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Swedish_sv', 'Swedish');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tajik_tg', 'Tajik');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tamil_ta', 'Tamil');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tatar_tt', 'Tatar');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Telugu_te', 'Telugu');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Thai_th', 'Thai');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tigrinya_ti', 'Tigrinya');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Tongan_to', 'Tongan');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Turkish_tr', 'Turkish');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Turkmen_tk', 'Turkmen');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Twi_tw', 'Twi');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Ukrainian_uk', 'Ukrainian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Urdu_ur', 'Urdu');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Uyghur_ug', 'Uyghur');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Uzbek_uz', 'Uzbek');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Vietnamese_vi', 'Vietnamese');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Walloon_wa', 'Walloon');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Welsh_cy', 'Welsh');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Western Frisian_fy', 'Western Frisian');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Xhosa_xh', 'Xhosa');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Yiddish_yi', 'Yiddish');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Yoruba_yo', 'Yoruba');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'Zulu_zu', 'Zulu');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'no_available_data', 'No available data to show.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'my_wallet_', 'My Wallet');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'you_have_bought_products', 'You have bought a product');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sale_products', 'Sale products');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'entire_site', 'Entire Site');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'banned_for_violating', 'You were banned for violating our terms of use. Please {contact_us} for more details.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'were_banned_from', 'Oops, You were banned from {site_name}');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'contact_us_more_details', 'Please {contact_us} for more details.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'pdf_file', 'Attach PDF File');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'certification_file', 'Certificate');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_currently_working', 'Are you currently working?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'am_looking_to_work', 'No I am looking to work');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'am_looking_for_employees', 'Yes I am looking for employees');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'products_for_sale', 'Products for sale');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_notifications_because_you_were_banned', 'You can\'t view your notifications because you were banned');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_messages_because_you_were_banned', 'You can\'t view your messages because you were banned');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'your_requests_because_you_were_banned', 'You can\'t view your requests because you were banned');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'withdrawal', 'Withdrawal');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'successfully_received_from', 'Money was successfully received from');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'terms_of_use_page', '<h4>1- Write your Terms Of Use here.</h4>
      Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod          tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,          quis sdnostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo          consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse          cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non          proident, sunt in culpa qui officia deserunt mollit anim id est laborum.          <br><br>          <h4>2- Random title</h4>          Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod          tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,          quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo          consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse          cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non          proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'privacy_policy_page', '<h2>Who we are?</h2>
      Provide name and contact details of the data controller. This will typically be your business or you, if you are a sole trader. Where applicable, you should include the identity and contact details of the controller’s representative and/or the data protection officer.

      <h2>What information do we collect?</h2>
      Specify the types of personal information you collect, eg names, addresses, user names, etc. You should include specific details on:
      how you collect data (eg when a user registers, purchases or uses your services, completes a contact form, signs up to a newsletter, etc)
      what specific data you collect through each of the data collection method
      if you collect data from third parties, you must specify categories of data and source
      if you process sensitive personal data or financial information, and how you handle this
      <br><br>
      You may want to provide the user with relevant definitions in relation to personal data and sensitive personal data.
      <br><br>
      <h2>How do we use personal information?</h2>
      Describe in detail all the service- and business-related purposes for which you will process data. For example, this may include things like:
      personalisation of content, business information or user experience
      account set up and administration
      delivering marketing and events communication
      carrying out polls and surveys
      internal research and development purposes
      providing goods and services
      legal obligations (eg prevention of fraud)
      meeting internal audit requirements
      <br><br>
      Please note this list is not exhaustive. You will need to record all purposes for which you process personal data.
      <br><br>
      <h2>What legal basis do we have for processing your personal data?</h2>
      Describe the relevant processing conditions contained within the GDPR. There are six possible legal grounds:
      consent
      contract
      legitimate interests
      vital interests
      public task
      legal obligation
      <br><br>
      Provide detailed information on all grounds that apply to your processing, and why. If you rely on consent, explain how individuals can withdraw and manage their consent. If you rely on legitimate interests, explain clearly what these are.
      <br><br>
      If you’re processing special category personal data, you will have to satisfy at least one of the six processing conditions, as well as additional requirements for processing under the GDPR. Provide information on all additional grounds that apply.
      <br><br>
      <h2>When do we share personal data?</h2>
      Explain that you will treat personal data confidentially and describe the circumstances when you might disclose or share it. Eg, when necessary to provide your services or conduct your business operations, as outlined in your purposes for processing. You should provide information on:
      how you will share the data
      what safeguards you will have in place
      what parties you may share the data with and why

      <h2>Where do we store and process personal data?</h2>
      If applicable, explain if you intend to store and process data outside of the data subject’s home country. Outline the steps you will take to ensure the data is processed according to your privacy policy and the applicable law of the country where data is located.

      If you transfer data outside the European Economic Area, outline the measures you will put in place to provide an appropriate level of data privacy protection. Eg contractual clauses, data transfer agreements, etc.

      <h2>How do we secure personal data?</h2>
      Describe your approach to data security and the technologies and procedures you use to protect personal information. For example, these may be measures:
      to protect data against accidental loss
      to prevent unauthorised access, use, destruction or disclosure
      to ensure business continuity and disaster recovery
      to restrict access to personal information
      to conduct privacy impact assessments in accordance with the law and your business policies
      to train staff and contractors on data security
      to manage third party risks, through use of contracts and security reviews
      <br><br>
      Please note this list is not exhaustive. You should record all mechanisms you rely on to protect personal data. You should also state if your organisation adheres to certain accepted standards or regulatory requirements.
      <br><br>
      <h2>How long do we keep your personal data for?</h2>
      Provide specific information on the length of time you will keep the information for in relation to each processing purpose. The GDPR requires you to retain data for no longer than reasonably necessary. Include details of your data or records retention schedules, or link to additional resources where these are published.
      <br><br>
      If you cannot state a specific period, you need to set out the criteria you will apply to determine how long to keep the data for (eg local laws, contractual obligations, etc)
      <br><br>
      You should also outline how you securely dispose of data after you no longer need it.
      <br><br>
      <h2>Your rights in relation to personal data</h2>
      Under the GDPR, you must respect the right of data subjects to access and control their personal data. In your privacy notice, you must outline their rights in respect of:
      access to personal information
      correction and deletion
      withdrawal of consent (if processing data on condition of consent)
      data portability
      restriction of processing and objection
      lodging a complaint with the Information Commissioner’s Office

      You should explain how individuals can exercise their rights, and how you plan to respond to subject data requests. State if any relevant exemptions may apply and set out any identity verifications procedures you may rely on.

      Include details of the circumstances where data subject rights may be limited, eg if fulfilling the data subject request may expose personal data about another person, or if you’re asked to delete data which you are required to keep by law.

      <h2>Use of automated decision-making and profiling</h2>
      Where you use profiling or other automated decision-making, you must disclose this in your privacy policy. In such cases, you must provide details on existence of any automated decision-making, together with information about the logic involved, and the likely significance and consequences of the processing of the individual.

      <h2>How to contact us?</h2>
      Explain how data subject can get in touch if they have questions or concerns about your privacy practices, their personal information, or if they wish to file a complaint. Describe all ways in which they can contact you – eg online, by email or postal mail.
      <br><br>
      If applicable, you may also include information on:
      <br><br>
      <h2>Use of cookies and other technologies</h2>
      You may include a link to further information, or describe within the policy if you intend to set and use cookies, tracking and similar technologies to store and manage user preferences on your website, advertise, enable content or otherwise analyse user and usage data. Provide information on what types of cookies and technologies you use, why you use them and how an individual can control and manage them.
      <br><br>
      Linking to other websites / third party content
      If you link to external sites and resources from your website, be specific on whether this constitutes endorsement, and if you take any responsibility for the content (or information contained within) any linked website.
      <br><br>
      You may wish to consider adding other optional clauses to your privacy policy, depending on your business’ circumstances.
      ');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'about_page', '<h4>1- Write about your website here.</h4>          Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod          tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,          quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo          consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse          cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non          proident, sunt in culpa qui officia deserunt mollit anim id est laborum.          <br><br>          <h4>2- Random title</h4>          Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod          tempor incididunt ut labore et dxzcolore magna aliqua. Ut enim ad minim veniam,          quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo          consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse          cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non          proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'refund_terms_page', '<h4>1- Write your Terms Of Use here.</h4>
      Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod          tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,          quis sdnostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo          consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse          cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non          proident, sunt in culpa qui officia deserunt mollit anim id est laborum.          <br><br>          <h4>2- Random title</h4>          Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod          tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,          quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo          consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse          cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non          proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'added_review_to_your_product', 'reviewed your product');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'coinbase', 'Coinbase');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'product_purchase', 'Product Purchase');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'sold_a_product', 'Product Sale');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'describe_your_review', 'Describe your review here..');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'related_prods', 'Related Products');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_open_work', 'Are you sure you want to delete?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_delete_services', 'Are you sure you want to delete these services?');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'search_find_job_at', 'Search, find and apply to job opportunities at');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'connect_with_friends', 'Connect with friends!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'login_connect_friends', 'Login into your {site_name} account and connect with your friends!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'remember_device', 'Remember this device');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'register_create_account', 'Create your {site_name} Account!');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'please_upgrade_to_upload', 'Just Pro users can upload Please upgrade to pro');
          $lang_update_queries[] = Wo_UpdateLangs($value, 'type_something_to_post', 'Your post content can\'t be empty.');
        }
    }
    if (!empty($lang_update_queries)) {
        foreach ($lang_update_queries as $key => $query) {
            $sql = mysqli_query($sqlConnect, $query);
        }
        foreach ($wo['config']['currency_symbol_array'] as $key => $value) {
        	if ($key == 'USD') {
        		$wo['config']['currency_symbol_array'][$key] = '$';
        	}
        	if ($key == 'EUR') {
        		$wo['config']['currency_symbol_array'][$key] = '€';
        	}
        	if ($key == 'JPY') {
        		$wo['config']['currency_symbol_array'][$key] = '¥';
        	}
        	if ($key == 'TRY') {
        		$wo['config']['currency_symbol_array'][$key] = '₺';
        	}
        	if ($key == 'GBP') {
        		$wo['config']['currency_symbol_array'][$key] = '£';
        	}
        	if ($key == 'RUB') {
        		$wo['config']['currency_symbol_array'][$key] = '₽';
        	}
        	if ($key == 'PLN') {
        		$wo['config']['currency_symbol_array'][$key] = 'zł';
        	}
        	if ($key == 'ILS') {
        		$wo['config']['currency_symbol_array'][$key] = '₪';
        	}
        	if ($key == 'BRL') {
        		$wo['config']['currency_symbol_array'][$key] = 'R$';
        	}
        	if ($key == 'INR') {
        		$wo['config']['currency_symbol_array'][$key] = '₹';
        	}
        }
        if (empty($wo['config']['currency_symbol_array']['JPY'])) {
        	$wo['config']['currency_symbol_array']['JPY'] = '¥';
        }
        Wo_SaveConfig('currency_symbol_array', json_encode($wo['config']['currency_symbol_array']));
    }
    if (!is_writable("./sources/server.php")) {
        @chmod("./sources/server.php", 0777);
    }
    $name = md5(microtime()) . '_updated.php';
    rename('update.php', $name);
}
?>
<html>
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
      <meta name="viewport" content="width=device-width, initial-scale=1"/>
      <title>Updating WoWonder</title>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <style>
         @import url('https://fonts.googleapis.com/css?family=Roboto:400,500');
         @media print {
            .wo_update_changelog {max-height: none !important; min-height: !important}
            .btn, .hide_print, .setting-well h4 {display:none;}
         }
         * {outline: none !important;}
         body {background: #f3f3f3;font-family: 'Roboto', sans-serif;}
         .light {font-weight: 400;}
         .bold {font-weight: 500;}
         .btn {height: 52px;line-height: 1;font-size: 16px;transition: all 0.3s;border-radius: 2em;font-weight: 500;padding: 0 28px;letter-spacing: .5px;}
         .btn svg {margin-left: 10px;margin-top: -2px;transition: all 0.3s;vertical-align: middle;}
         .btn:hover svg {-webkit-transform: translateX(3px);-moz-transform: translateX(3px);-ms-transform: translateX(3px);-o-transform: translateX(3px);transform: translateX(3px);}
         .btn-main {color: #ffffff;background-color: #a84849;border-color: #a84849;}
         .btn-main:disabled, .btn-main:focus {color: #fff;}
         .btn-main:hover {color: #ffffff;background-color: #c45a5b;border-color: #c45a5b;box-shadow: -2px 2px 14px rgba(168, 72, 73, 0.35);}
         svg {vertical-align: middle;}
         .main {color: #a84849;}
         .wo_update_changelog {
          border: 1px solid #eee;
          padding: 10px !important;
         }
         .content-container {display: -webkit-box; width: 100%;display: -moz-box;display: -ms-flexbox;display: -webkit-flex;display: flex;-webkit-flex-direction: column;flex-direction: column;min-height: 100vh;position: relative;}
         .content-container:before, .content-container:after {-webkit-box-flex: 1;box-flex: 1;-webkit-flex-grow: 1;flex-grow: 1;content: '';display: block;height: 50px;}
         .wo_install_wiz {position: relative;background-color: white;box-shadow: 0 1px 15px 2px rgba(0, 0, 0, 0.1);border-radius: 10px;padding: 20px 30px;border-top: 1px solid rgba(0, 0, 0, 0.04);}
         .wo_install_wiz h2 {margin-top: 10px;margin-bottom: 30px;display: flex;align-items: center;}
         .wo_install_wiz h2 span {margin-left: auto;font-size: 15px;}
         .wo_update_changelog {padding:0;list-style-type: none;margin-bottom: 15px;max-height: 440px;overflow-y: auto; min-height: 440px;}
         .wo_update_changelog li {margin-bottom:7px; max-height: 20px; overflow: hidden;}
         .wo_update_changelog li span {padding: 2px 7px;font-size: 12px;margin-right: 4px;border-radius: 2px;}
         .wo_update_changelog li span.added {background-color: #4CAF50;color: white;}
         .wo_update_changelog li span.changed {background-color: #e62117;color: white;}
         .wo_update_changelog li span.improved {background-color: #9C27B0;color: white;}
         .wo_update_changelog li span.compressed {background-color: #795548;color: white;}
         .wo_update_changelog li span.fixed {background-color: #2196F3;color: white;}
         input.form-control {background-color: #f4f4f4;border: 0;border-radius: 2em;height: 40px;padding: 3px 14px;color: #383838;transition: all 0.2s;}
input.form-control:hover {background-color: #e9e9e9;}
input.form-control:focus {background: #fff;box-shadow: 0 0 0 1.5px #a84849;}
         .empty_state {margin-top: 80px;margin-bottom: 80px;font-weight: 500;color: #6d6d6d;display: block;text-align: center;}
         .checkmark__circle {stroke-dasharray: 166;stroke-dashoffset: 166;stroke-width: 2;stroke-miterlimit: 10;stroke: #7ac142;fill: none;animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;}
         .checkmark {width: 80px;height: 80px; border-radius: 50%;display: block;stroke-width: 3;stroke: #fff;stroke-miterlimit: 10;margin: 100px auto 50px;box-shadow: inset 0px 0px 0px #7ac142;animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;}
         .checkmark__check {transform-origin: 50% 50%;stroke-dasharray: 48;stroke-dashoffset: 48;animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;}
         @keyframes stroke { 100% {stroke-dashoffset: 0;}}
         @keyframes scale {0%, 100% {transform: none;}  50% {transform: scale3d(1.1, 1.1, 1); }}
         @keyframes fill { 100% {box-shadow: inset 0px 0px 0px 54px #7ac142; }}
      </style>
   </head>
   <body>
      <div class="content-container container">
         <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
               <div class="wo_install_wiz">
                 <?php if ($updated == false) { ?>
                  <div>
                     <h2 class="light">Update to v4.0</span></h2>
                     <div class="setting-well">
                        <h4>Changelog</h4>
                        <ul class="wo_update_changelog">
                        <li> [Added] website mode, switch your website instantly to Linkedin mode. (Instagram, Twitter, AskFM, Patreon are coming soon).</li>
                        <li> [Added] marketplace system, users can now buy and sell products + admin commission. </li>
                        <li> [Added] moderator rules manager, now you can choose what a moderator can do.</li>
                        <li> [Added] more ads placements (jobs, forums, movies, offers & funding) & entire site option.</li>
                        <li> [Added] multiple levels affiliate system.</li>
                        <li> [Added] custom ban message for every user. </li>
                        <li> [Added] the ability to translate terms pages.</li>
                        <li> [Added] CoinBase payment method. </li>
                        <li> [Added] new search system for Linkedin mode. </li>
                        <li> [Added] more APIs. </li>
                        <li> [Added] ReCaptcha to create article page. </li>
                        <li> [Added] Wasabi Storage.</li>
                        <li> [Added] new welcome page.</li>
                        <li> [Added] remember me on login page.</li>
                        <li> [Added] mutli languages support for terms, privacy and about pages.</li>
                        <li> [Updated] bulksms API.</li>
                        <li> [Updated] CoinPayments API.</li>
                        <li> [Updated] Infobip API.</li>
                        <li> [Updated] marketplace, jobs & movies pages design (default theme). </li>
                        <li> [Updated] desgin in a few other pages (default theme).</li>
                        <li> [Updated] Twilio SDK. </li>
                        <li> [Updated] left sidebar icons. (default theme)</li>
                        <li> [Updated] documentation & FAQs: <a href="https://docs.wowonder.com/" target="_blank">https://docs.wowonder.com/</a> .</li>
                        <li> [Cleaned] 10,000+ lines of outdated code. </li>
                        <li> [Orginzed] PHP code format (HTML coming in next update).</li>
                        <li> [Fixed] images with _small extensions is not getting deleted on remote storage.</li>
                        <li> [Fixed] watermark isn't working on images posts.</li>
                        <li> [Fixed] family memebers texts.</li>
                        <li> [Fixed] auto username, pages likes or groups joins isn't working when you register using the app.</li>
                        <li> [Fixed] login with instagram isn't working.</li>
                        <li> [Fixed] email notifications are not working on follow, view profile or comments.</li>
                        <li> [Fixed] digitalocean test button wasn't working in admin panel.</li>
                        <li> [Fixed] Audio/video calls are not working from website to apps using agora.</li>
                        <li> [Fixed] "common things" page php fatal error.</li>
                        <li> [Fixed] 2 XSS exploits. <small style="color: red;">[Important!]</small>  </li>
                        <li> [Fixed] blank page when adding / in url.</li>
                        <li> [Fixed] site-map doesn't generate more than 50K links.</li>
                        <li> [Fixed] read more button on mobile.</li>
                        <li> [Fixed] 30+ more minor bugs.</li>
                        <li> [Fixed] bugs in API.</li>
                        </ul>
                        <p class="hide_print">Note: The update process might take few minutes.</p>
                        <p class="hide_print">Important: If you got any fail queries, please copy them, open a support ticket and send us the details.</p>
                        <p class="hide_print">Most of the features are disabled by default, you can enable them from Admin -> Manage Features -> Enable / Disable Features, reaction can be enabled from Settings > Posts Sttings.</p><br>
                        <p class="hide_print">Please enter your valid purchase code:</p>
                        <input type="text" id="input_code" class="form-control" placeholder="Your Envato purchase code" style="padding: 10px; width: 50%;"><br>

                        <br>
                             <button class="pull-right btn btn-default" onclick="window.print();">Share Log</button>
                             <button type="button" class="btn btn-main" id="button-update" disabled>
                             Update
                             <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg" width="18" height="18">
                                <path fill="currentColor" d="M18.6 6.9v-.5l-6-6c-.3-.3-.9-.3-1.2 0-.3.3-.3.9 0 1.2l5 5H1c-.5 0-.9.4-.9.9s.4.8.9.8h14.4l-4 4.1c-.3.3-.3.9 0 1.2.2.2.4.2.6.2.2 0 .4-.1.6-.2l5.2-5.2h.2c.5 0 .8-.4.8-.8 0-.3 0-.5-.2-.7z"></path>
                             </svg>
                          </button>
                     </div>
                     <?php }?>
                     <?php if ($updated == true) { ?>
                      <div>
                        <div class="empty_state">
                           <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                              <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                              <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                           </svg>
                           <p>Congratulations, you have successfully updated your site. Thanks for choosing WoWonder.</p>
                           <br>
                           <a href="<?php echo $wo['config']['site_url'] ?>" class="btn btn-main" style="line-height:50px;">Home</a>
                        </div>
                     </div>
                     <?php }?>
                  </div>
               </div>
            </div>
            <div class="col-md-1"></div>
         </div>
      </div>
   </body>
</html>
<script>
var queries = [
    "UPDATE `Wo_Config` SET `value` = '4.0' WHERE `name` = 'version';",
    "ALTER TABLE `Wo_Manage_Pro` CHANGE `time` `time` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'week';",
    "UPDATE `Wo_Manage_Pro` SET `time` = 'week' WHERE `Wo_Manage_Pro`.`id` = 1;",
    "UPDATE `Wo_Manage_Pro` SET `time` = 'month' WHERE `Wo_Manage_Pro`.`id` = 2;",
    "UPDATE `Wo_Manage_Pro` SET `time` = 'year' WHERE `Wo_Manage_Pro`.`id` = 3;",
    "UPDATE `Wo_Manage_Pro` SET `time` = 'unlimited' WHERE `Wo_Manage_Pro`.`id` = 4;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'msg91_dlt_id', '');",
    "ALTER TABLE `Wo_Users` ADD `permission` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `time_code_sent`;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'store_system', 'on');",
    "CREATE TABLE `Wo_UserCard` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `user_id` int(11) NOT NULL DEFAULT 0,  `product_id` int(11) NOT NULL DEFAULT 0,  `units` int(11) NOT NULL DEFAULT 0,  PRIMARY KEY (`id`),  KEY `user_id` (`user_id`),  KEY `product_id` (`product_id`),  KEY `units` (`units`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;",
    "ALTER TABLE `Wo_Products` ADD `units` INT(11) NOT NULL DEFAULT '0' AFTER `lat`, ADD INDEX (`units`);",
    "CREATE TABLE `Wo_UserAddress` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `user_id` int(11) NOT NULL DEFAULT 0,  `name` varchar(100) NOT NULL DEFAULT '',  `phone` varchar(50) NOT NULL DEFAULT '',  `country` varchar(100) NOT NULL DEFAULT '',  `city` varchar(100) NOT NULL DEFAULT '',  `zip` varchar(20) NOT NULL DEFAULT '',  `address` varchar(500) NOT NULL DEFAULT '',  `time` int(11) NOT NULL DEFAULT 0,  PRIMARY KEY (`id`),  KEY `user_id` (`user_id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'exchange', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'exchange_update', '');",
    "UPDATE `Wo_Config` SET `value` = '{\"USD\":\"&#36;\",\"EUR\":\"&#8364;\",\"JPY\":\"&#165;\",\"TRY\":\"&#8378;\",\"GBP\":\"&#163;\",\"RUB\":\"&#8381;\",\"PLN\":\"&#122;&#322;\",\"ILS\":\"&#8362;\",\"BRL\":\"&#82;&#36;\",\"INR\":\"&#8377;\"}' WHERE `Wo_Config`.`name` = 'currency_symbol_array';",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'store_commission', '0');",
    "CREATE TABLE `Wo_UserOrders` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `hash_id` varchar(100) NOT NULL DEFAULT '',  `user_id` int(11) NOT NULL DEFAULT 0,  `product_owner_id` int(11) NOT NULL DEFAULT 0,  `product_id` int(11) NOT NULL DEFAULT 0,  `address_id` int(11) NOT NULL DEFAULT 0,  `price` float NOT NULL DEFAULT 0,  `commission` float NOT NULL DEFAULT 0,  `final_price` float NOT NULL DEFAULT 0,  `units` int(11) NOT NULL DEFAULT 0,  `tracking_url` varchar(500) NOT NULL DEFAULT '',  `tracking_id` varchar(50) NOT NULL DEFAULT '',  `status` varchar(30) NOT NULL DEFAULT 'placed',  `time` int(11) NOT NULL DEFAULT 0,  PRIMARY KEY (`id`),  KEY `user_id` (`user_id`),  KEY `product_owner_id` (`product_owner_id`),  KEY `product_id` (`product_id`),  KEY `final_price` (`final_price`),  KEY `status` (`status`),  KEY `time` (`time`),  KEY `hash_id` (`hash_id`),  KEY `units` (`units`),  KEY `address_id` (`address_id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;",
    "CREATE TABLE `Wo_Purchases` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `user_id` int(11) NOT NULL DEFAULT 0,  `order_hash_id` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',  `owner_id` int(11) NOT NULL DEFAULT 0,  `data` text CHARACTER SET utf8 DEFAULT NULL,  `final_price` float NOT NULL DEFAULT 0,  `commission` float NOT NULL DEFAULT 0,  `price` float NOT NULL DEFAULT 0,  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),  `time` int(11) NOT NULL DEFAULT 0,  PRIMARY KEY (`id`),  KEY `user_id` (`user_id`),  KEY `timestamp` (`timestamp`),  KEY `time` (`time`),  KEY `owner_id` (`owner_id`),  KEY `final_price` (`final_price`),  KEY `order_hash_id` (`order_hash_id`),  KEY `data` (`data`(1024))) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;",
    "CREATE TABLE `Wo_ProductReview` (  `id` int(11) NOT NULL AUTO_INCREMENT,  `user_id` int(11) NOT NULL DEFAULT 0,  `product_id` int(11) NOT NULL DEFAULT 0,  `review` text DEFAULT NULL,  `star` int(11) NOT NULL DEFAULT 1,  `time` int(11) NOT NULL DEFAULT 0,  PRIMARY KEY (`id`),  KEY `user_id` (`user_id`),  KEY `product_id` (`product_id`),  KEY `star` (`star`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;",
    "ALTER TABLE `Wo_Refund` ADD `order_hash_id` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `user_id`;",
    "ALTER TABLE `Wo_Albums_Media` ADD `review_id` INT(11) NOT NULL DEFAULT '0' AFTER `parent_id`, ADD INDEX (`review_id`);",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'store_review_system', 'off');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'website_mode', 'facebook');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'post_location', '1');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'post_feelings', '1');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'post_poll', '1');",
    "CREATE TABLE `Wo_PatreonSubscribers` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `user_id` INT(11) NOT NULL DEFAULT '0' , `subscriber_id` INT(11) NOT NULL DEFAULT '0' , `time` INT(11) NOT NULL DEFAULT '0' , PRIMARY KEY (`id`), INDEX (`user_id`), INDEX (`subscriber_id`), INDEX (`time`)) ENGINE = InnoDB;",
    "CREATE TABLE `Wo_UserExperience` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `user_id` INT(11) NOT NULL DEFAULT '0' , `title` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' , `location` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' , `experience_start` DATE NOT NULL , `experience_end` DATE NOT NULL , `industry` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' , `description` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL , `image` VARCHAR(300) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' , `link` VARCHAR(300) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' , `headline` VARCHAR(150) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' , `company_name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' , `employment_type` VARCHAR(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' , `time` INT(11) NOT NULL DEFAULT '0' , PRIMARY KEY (`id`), INDEX (`user_id`), INDEX (`time`)) ENGINE = InnoDB;",
    "CREATE TABLE `Wo_UserCertification` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `user_id` INT(11) NOT NULL DEFAULT '0' , `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' , `issuing_organization` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' , `credential_id` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' , `credential_url` VARCHAR(300) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' , `certification_start` DATE NOT NULL , `certification_end` DATE NOT NULL , `time` INT(11) NOT NULL DEFAULT '0' , PRIMARY KEY (`id`), INDEX (`user_id`)) ENGINE = InnoDB;",
    "CREATE TABLE `Wo_UserProjects` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `user_id` INT(11) NOT NULL DEFAULT '0' , `name` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' , `description` VARCHAR(600) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' , `associated_with` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' , `project_url` VARCHAR(300) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' , `project_start` DATE NOT NULL , `project_end` DATE NOT NULL , `time` INT(11) NOT NULL DEFAULT '0' , PRIMARY KEY (`id`), INDEX (`user_id`), INDEX (`name`)) ENGINE = InnoDB;",
    "ALTER TABLE `Wo_Users` ADD `skills` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `permission`;",
    "ALTER TABLE `Wo_Users` ADD `languages` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `skills`;",
    "CREATE TABLE `Wo_UserOpenTo` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `user_id` INT(11) NOT NULL DEFAULT '0' , `job_title` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' , `job_location` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' , `workplaces` VARCHAR(600) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' , `job_type` VARCHAR(600) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' , `type` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' , `time` INT(11) NOT NULL DEFAULT '0' , PRIMARY KEY (`id`), INDEX (`user_id`), INDEX (`job_title`), INDEX (`job_location`), INDEX (`workplaces`), INDEX (`job_type`), INDEX (`type`), INDEX (`time`)) ENGINE = InnoDB;",
    "ALTER TABLE `Wo_UserOpenTo` ADD `services` VARCHAR(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `job_type`, ADD `description` VARCHAR(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `services`, ADD INDEX (`services`), ADD INDEX (`description`);",
    "CREATE TABLE `Wo_UserTiers` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `user_id` INT(11) NOT NULL DEFAULT '0' , `title` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' , `price` FLOAT(11) NOT NULL DEFAULT '0' , `image` VARCHAR(400) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' , `description` VARCHAR(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' , `chat` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' , `live_stream` INT(11) NOT NULL DEFAULT '0' , `time` INT(11) NOT NULL DEFAULT '0' , PRIMARY KEY (`id`), INDEX (`user_id`), INDEX (`chat`), INDEX (`live_stream`)) ENGINE = InnoDB;",
    "ALTER TABLE `Wo_Posts` CHANGE `postPrivacy` `postPrivacy` ENUM('0','1','2','3','4','5') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1';",
    "CREATE TABLE `Wo_UserSkills` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `name` VARCHAR(300) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' , PRIMARY KEY (`id`), INDEX (`name`)) ENGINE = InnoDB;",
    "CREATE TABLE `Wo_UserLanguages` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `lang_key` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' , PRIMARY KEY (`id`), INDEX (`lang_key`)) ENGINE = InnoDB;",
    "INSERT INTO `Wo_UserLanguages` (`id`, `lang_key`) VALUES (NULL, 'Afrikaans_af'),(NULL, 'Albanian_sq'),(NULL, 'Amharic_am'),(NULL, 'Arabic_ar'),(NULL, 'Aragonese_an'),(NULL, 'Armenian_hy'),(NULL, 'Asturian_ast'),(NULL, 'Azerbaijani_az'),(NULL, 'Basque_eu'),(NULL, 'Belarusian_be'),(NULL, 'Bengali_bn'),(NULL, 'Bosnian_bs'),(NULL, 'Breton_br'),(NULL, 'Bulgarian_bg'),(NULL, 'Catalan_ca'),(NULL, 'Central Kurdish_ckb'),(NULL, 'Chinese_zh'),(NULL, 'Corsican_co'),(NULL, 'Croatian_hr'),(NULL, 'Czech_cs'),(NULL, 'Danish_da'),(NULL, 'Dutch_nl'),(NULL, 'English_en'),(NULL, 'Esperanto_eo'),(NULL, 'Estonian_et'),(NULL, 'Faroese_fo'),(NULL, 'Filipino_fil'),(NULL, 'Finnish_fi'),(NULL, 'French_fr'),(NULL, 'Galician_gl'),(NULL, 'Georgian_ka'),(NULL, 'German_de'),(NULL, 'Greek_el'),(NULL, 'Guarani_gn'),(NULL, 'Gujarati_gu'),(NULL, 'Hausa_ha'),(NULL, 'Hawaiian_haw'),(NULL, 'Hebrew_he'),(NULL, 'Hindi_hi'),(NULL, 'Hungarian_hu'),(NULL, 'Icelandic_is'),(NULL, 'Indonesian_id'),(NULL, 'Interlingua_ia'),(NULL, 'Irish_ga'),(NULL, 'Italian_it'),(NULL, 'Japanese_ja'),(NULL, 'Kannada_kn'),(NULL, 'Kazakh_kk'),(NULL, 'Khmer_km'),(NULL, 'Korean_ko'),(NULL, 'Kurdish_ku'),(NULL, 'Kyrgyz_ky'),(NULL, 'Lao_lo'),(NULL, 'Latin_la'),(NULL, 'Latvian_lv'),(NULL, 'Lingala_ln'),(NULL, 'Lithuanian_lt'),(NULL, 'Macedonian_mk'),(NULL, 'Malay_ms'),(NULL, 'Malayalam_ml'),(NULL, 'Maltese_mt'),(NULL, 'Marathi_mr'),(NULL, 'Mongolian_mn'),(NULL, 'Nepali_ne'),(NULL, 'Norwegian_no'),(NULL, 'Norwegian Bokmål_nb'),(NULL, 'Norwegian Nynorsk_nn'),(NULL, 'Occitan_oc'),(NULL, 'Oriya_or'),(NULL, 'Oromo_om'),(NULL, 'Pashto_ps'),(NULL, 'Persian_fa'),(NULL, 'Polish_pl'),(NULL, 'Portuguese_pt'),(NULL, 'Punjabi_pa'),(NULL, 'Quechua_qu'),(NULL, 'Romanian_ro'),(NULL, 'Romansh_rm'),(NULL, 'Russian_ru'),(NULL, 'Scottish Gaelic_gd'),(NULL, 'Serbian_sr'),(NULL, 'Serbo_sh'),(NULL, 'Shona_sn'),(NULL, 'Sindhi_sd'),(NULL, 'Sinhala_si'),(NULL, 'Slovak_sk'),(NULL, 'Slovenian_sl'),(NULL, 'Somali_so'),(NULL, 'Southern Sotho_st'),(NULL, 'Spanish_es'),(NULL, 'Sundanese_su'),(NULL, 'Swahili_sw'),(NULL, 'Swedish_sv'),(NULL, 'Tajik_tg'),(NULL, 'Tamil_ta'),(NULL, 'Tatar_tt'),(NULL, 'Telugu_te'),(NULL, 'Thai_th'),(NULL, 'Tigrinya_ti'),(NULL, 'Tongan_to'),(NULL, 'Turkish_tr'),(NULL, 'Turkmen_tk'),(NULL, 'Twi_tw'),(NULL, 'Ukrainian_uk'),(NULL, 'Urdu_ur'),(NULL, 'Uyghur_ug'),(NULL, 'Uzbek_uz'),(NULL, 'Vietnamese_vi'),(NULL, 'Walloon_wa'),(NULL, 'Welsh_cy'),(NULL, 'Western Frisian_fy'),(NULL, 'Xhosa_xh'),(NULL, 'Yiddish_yi'),(NULL, 'Yoruba_yo'),(NULL, 'Zulu_zu');",
    "ALTER TABLE `Wo_Banned_Ip` ADD `reason` VARCHAR(1000) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `ip_address`;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'affiliate_level', '1');",
    "ALTER TABLE `Wo_Users` ADD `ref_level` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `ref_user_id`;",
    "ALTER TABLE `Wo_UserCertification` ADD `pdf` VARCHAR(300) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `certification_end`;",
    "ALTER TABLE `Wo_UserCertification` ADD `filename` VARCHAR(200) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `pdf`;",
    "ALTER TABLE `Wo_Users` ADD `currently_working` VARCHAR(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `languages`, ADD INDEX (`currently_working`);",
    "ALTER TABLE `Wo_Users` ADD `banned` INT(5) NOT NULL DEFAULT '0' AFTER `currently_working`, ADD INDEX (`banned`);",
    "ALTER TABLE `Wo_Users` ADD `banned_reason` VARCHAR(500) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `banned`;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'coinbase_payment', '0');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'coinbase_key', '');",
    "ALTER TABLE `Wo_Users` ADD `coinbase_hash` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `banned_reason`, ADD `coinbase_code` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `coinbase_hash`, ADD INDEX (`coinbase_hash`), ADD INDEX (`coinbase_code`);",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'wasabi_storage', '0');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'wasabi_access_key', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'wasabi_secret_key', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'wasabi_bucket_name', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'wasabi_bucket_region', 'us-west-1');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'remember_device', '1');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'infobip_api_key', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'infobip_base_url', '');",
    "ALTER TABLE `Wo_Mute` CHANGE `archive` `archive` VARCHAR(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'no', CHANGE `pin` `pin` VARCHAR(5) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'no';",
    "ALTER TABLE `Wo_Messages` ADD `listening` INT(11) NOT NULL DEFAULT '0' AFTER `forward`, ADD INDEX (`listening`);",
    "DROP INDEX lang_key ON Wo_Langs;",
    "DROP INDEX name ON Wo_Config;",
    "ALTER IGNORE TABLE Wo_Langs ADD UNIQUE INDEX idx_name (lang_key);",
    "ALTER IGNORE TABLE Wo_Config ADD UNIQUE INDEX idx_name (name);",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'you_must_add_text_or_image_first');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'add_to_cart');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'remove_from_cart');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'pay_from_wallet');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'please_top_up_wallet');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'pay_to_upgrade');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'pay_to_fund');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'amount_can_not_empty');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'fund_not_found');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'payment_successfully_done');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'buy_now');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'total_item');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'total_item_not_empty');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'currently_unavailable');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'checkout');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'no_items_found');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'total');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'my_addresses');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'add_new');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'add_new_address');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'address_added');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'delete_your_address');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'are_you_delete_your_address');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'edit_address');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'address_edited');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'please_add_address');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'please_select_address');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'payment_alert');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'purchase_the_items');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'shopping_cart');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'items');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'back_to_shop');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'some_products_units');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'address_can_not_be_empty');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'address_not_found');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'card_is_empty');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'your_order_has_been_placed_successfully');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'purchased');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'no_purchased_found');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'order');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'download_invoice');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'id_can_not_empty');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'you_are_not_purchased');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'order_not_found');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'order_details');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'write_review');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'tracking_details');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'delivery_address');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'if_the_order_status');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'if_the_order_delivered');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'placed');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'payments');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'subtotal');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'sale_invoice');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'seller_name');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'seller_email');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'invoice_to');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'payment_details');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'total_due');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'bank_name');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'invoice');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'item');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'orders');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'no_orders_found');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'products');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'qty');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'canceled');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'accepted');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'packed');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'shipped');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'commission');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'final_price');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'tracking_number');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'link');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'tracking_info_has_been_saved_successfully');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'tracking_url_can_not_be_empty');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'tracking_number_can_not_be_empty');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'please_enter_valid_url');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'site_url');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'delivered');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'please_explain_the_reason');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'your_request_is_under_review');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'review');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'submit');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'review_can_not_be_empty');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'rating_can_not_be_empty');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'review_has_been_sent_successfully');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'admin_status_changed');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'new_orders_has_been_placed');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'added_tracking_info');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'product_approved');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'your_product_is_under_review');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'tweet');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'ask');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'write_answer');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'reply_to_answer');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'answered_your_question');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'replied_to_answer');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'liked_question');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'liked_answer');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'answer_mention');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'question_mention');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'verified_purchase');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'no_reviews_found');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'ask_anonymously');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'ask_friend');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'search_for_friends');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'askfm_box_placeholder');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'question_can_not_empty');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'please_select_who_you_want_ask');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'asked_you_a_question');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'trending_questions');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'people_liked_question');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'users_liked_answer');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'no_answers_found');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'search_header_people');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'tweets');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'trending_tweets');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'people_liked_tweet');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'liked_tweet');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'please_select_a_file_to_upload');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'unlock_content_post_text');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'join_now');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'patreon_membership_price');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'add_new_experience');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'company_name');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'employment_type');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'self_employed');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'freelance');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'apprenticeship');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'seasonal');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'industry');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'title_empty');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'company_name_empty');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'employment_type_empty');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'location_empty');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'start_date_empty');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'industry_empty');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'description_empty');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'please_choose_correct_experience_date');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'experience_successfully_created');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'valid_link');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'delete_your_experience');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'are_you_delete_your_experience');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'edit_experience');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'you_not_owner');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'experience_successfully_updated');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'certifications');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'licenses_certifications');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'add_new_certification');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'issuing_organization');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'credential_id');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'credential_url');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'issuing_organization_empty');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'issue_date');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'expiration_date');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'issue_date_empty');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'name_empty');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'certification_successfully_created');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'delete_your_certification');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'are_you_delete_your_certification');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'edit_certification');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'certification_successfully_updated');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'projects');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'add_new_project');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'project_name');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'associated_with');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'project_url');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'project_successfully_added');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'delete_your_project');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'are_you_delete_your_project');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'edit_project');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'project_successfully_updated');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'skills');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'languages');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'open_to');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'finding_a_job');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'providing_services');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'hiring');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'add_job_preferences');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'tell_us_kind_work');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'workplaces');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'on_site');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'hybrid');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'remote');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'job_types');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'temporary');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'job_location');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Job_title_empty');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'job_location_empty');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'workplaces_empty');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'job_type_empty');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'job_preferences_saved_successfully');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'open_to_work');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'see_all_details');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'job_preferences');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'set_up_services_page');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'services');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'services_empty');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'services_saved_successfully');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'services_provided');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'invalid_id');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'services_edited_successfully');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'edit_job_preferences');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'job_preferences_edited_successfully');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'load_more_services');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'tiers');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'choose_offer_patrons');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'add_tier');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'tier_title');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'tier_price');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'tier_image');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'tier_description');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'benefits');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'chat_without_audio_video');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'chat_with_audio_without_video');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'chat_without_audio_with_video');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'chat_with_audio_video');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'chat');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'live_stream');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'price_empty');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'benefits_empty');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'please_select_chat_type');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'tier_added_successfully');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'edit_tier');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'tier_updated_successfully');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'delete_your_tier');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'are_you_delete_your_tier');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'patron');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'patrons');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'services_you_may_know');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'open_to_work_posts');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Afrikaans_af');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Albanian_sq');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Amharic_am');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Arabic_ar');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Aragonese_an');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Armenian_hy');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Asturian_ast');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Azerbaijani_az');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Basque_eu');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Belarusian_be');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Bengali_bn');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Bosnian_bs');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Breton_br');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Bulgarian_bg');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Catalan_ca');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Central Kurdish_ckb');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Chinese_zh');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Corsican_co');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Croatian_hr');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Czech_cs');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Danish_da');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Dutch_nl');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'English_en');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Esperanto_eo');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Estonian_et');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Faroese_fo');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Filipino_fil');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Finnish_fi');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'French_fr');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Galician_gl');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Georgian_ka');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'German_de');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Greek_el');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Guarani_gn');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Gujarati_gu');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Hausa_ha');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Hawaiian_haw');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Hebrew_he');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Hindi_hi');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Hungarian_hu');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Icelandic_is');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Indonesian_id');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Interlingua_ia');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Irish_ga');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Italian_it');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Japanese_ja');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Kannada_kn');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Kazakh_kk');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Khmer_km');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Korean_ko');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Kurdish_ku');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Kyrgyz_ky');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Lao_lo');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Latin_la');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Latvian_lv');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Lingala_ln');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Lithuanian_lt');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Macedonian_mk');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Malay_ms');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Malayalam_ml');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Maltese_mt');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Marathi_mr');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Mongolian_mn');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Nepali_ne');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Norwegian_no');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Norwegian Bokmål_nb');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Norwegian Nynorsk_nn');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Occitan_oc');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Oriya_or');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Oromo_om');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Pashto_ps');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Persian_fa');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Polish_pl');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Portuguese_pt');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Punjabi_pa');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Quechua_qu');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Romanian_ro');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Romansh_rm');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Russian_ru');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Scottish Gaelic_gd');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Serbian_sr');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Serbo_sh');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Shona_sn');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Sindhi_sd');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Sinhala_si');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Slovak_sk');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Slovenian_sl');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Somali_so');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Southern Sotho_st');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Spanish_es');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Sundanese_su');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Swahili_sw');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Swedish_sv');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Tajik_tg');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Tamil_ta');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Tatar_tt');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Telugu_te');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Thai_th');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Tigrinya_ti');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Tongan_to');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Turkish_tr');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Turkmen_tk');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Twi_tw');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Ukrainian_uk');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Urdu_ur');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Uyghur_ug');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Uzbek_uz');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Vietnamese_vi');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Walloon_wa');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Welsh_cy');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Western Frisian_fy');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Xhosa_xh');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Yiddish_yi');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Yoruba_yo');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'Zulu_zu');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'no_available_data');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'my_wallet_');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'you_have_bought_products');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'sale_products');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'entire_site');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'banned_for_violating');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'were_banned_from');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'contact_us_more_details');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'pdf_file');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'certification_file');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'are_you_currently_working');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'am_looking_to_work');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'am_looking_for_employees');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'products_for_sale');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'your_notifications_because_you_were_banned');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'your_messages_because_you_were_banned');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'your_requests_because_you_were_banned');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'withdrawal');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'successfully_received_from');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'terms_of_use_page');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'privacy_policy_page');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'about_page');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'refund_terms_page');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'added_review_to_your_product');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'coinbase');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'product_purchase');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'sold_a_product');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'describe_your_review');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'related_prods');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'are_you_delete_open_work');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'are_you_delete_services');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'search_find_job_at');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'connect_with_friends');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'login_connect_friends');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'remember_device');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'register_create_account');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'please_upgrade_to_upload');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'type_something_to_post');",


];

$('#input_code').bind("paste keyup input propertychange", function(e) {
    if (isPurchaseCode($(this).val())) {
        $('#button-update').removeAttr('disabled');
    } else {
        $('#button-update').attr('disabled', 'true');
    }
});

function isPurchaseCode(str) {
    var patt = new RegExp("(.*)-(.*)-(.*)-(.*)-(.*)");
    var res = patt.test(str);
    if (res) {
        return true;
    }
    return false;
}

$(document).on('click', '#button-update', function(event) {
    if ($('body').attr('data-update') == 'true') {
        window.location.href = '<?php echo $wo['config']['site_url']?>';
        return false;
    }
    $(this).attr('disabled', true);
    var PurchaseCode = $('#input_code').val();
    $.post('?check', {code: PurchaseCode}, function(data, textStatus, xhr) {
        if (data.status == 200) {
            $('.wo_update_changelog').html('');
            $('.wo_update_changelog').css({
                background: '#1e2321',
                color: '#fff'
            });
            $('.setting-well h4').text('Updating..');
            $(this).attr('disabled', true);
            RunQuery();
        } else {
            $(this).removeAttr('disabled');
            alert(data.error);
        }
    });
});

var queriesLength = queries.length;
var query = queries[0];
var count = 0;
function b64EncodeUnicode(str) {
    // first we use encodeURIComponent to get percent-encoded UTF-8,
    // then we convert the percent encodings into raw bytes which
    // can be fed into btoa.
    return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g,
        function toSolidBytes(match, p1) {
            return String.fromCharCode('0x' + p1);
    }));
}
function RunQuery() {
    var query = queries[count];
    $.post('?update', {
        query: b64EncodeUnicode(query)
    }, function(data, textStatus, xhr) {
        if (data.status == 200) {
            $('.wo_update_changelog').append('<li><span class="added">SUCCESS</span> ~$ mysql > ' + query + '</li>');
        } else {
            $('.wo_update_changelog').append('<li><span class="changed">FAILED</span> ~$ mysql > ' + query + '</li>');
        }
        count = count + 1;
        if (queriesLength > count) {
            setTimeout(function() {
                RunQuery();
            }, 1500);
        } else {
            $('.wo_update_changelog').append('<li><span class="added">Updating Langauges</span> ~$ languages.sh, Please wait, this might take some time..</li>');
            $.post('?run_lang', {
                update_langs: 'true'
            }, function(data, textStatus, xhr) {
              $('.wo_update_changelog').append('<li><span class="fixed">Finished!</span> ~$ Congratulations! you have successfully updated your site. Thanks for choosing WoWonder.</li>');
              $('.setting-well h4').text('Update Log');
              $('#button-update').html('Home <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg" width="18" height="18"> <path fill="currentColor" d="M18.6 6.9v-.5l-6-6c-.3-.3-.9-.3-1.2 0-.3.3-.3.9 0 1.2l5 5H1c-.5 0-.9.4-.9.9s.4.8.9.8h14.4l-4 4.1c-.3.3-.3.9 0 1.2.2.2.4.2.6.2.2 0 .4-.1.6-.2l5.2-5.2h.2c.5 0 .8-.4.8-.8 0-.3 0-.5-.2-.7z"></path> </svg>');
              $('#button-update').attr('disabled', false);
              $(".wo_update_changelog").scrollTop($(".wo_update_changelog")[0].scrollHeight);
              $('body').attr('data-update', 'true');
            });
        }
        $(".wo_update_changelog").scrollTop($(".wo_update_changelog")[0].scrollHeight);
    });
}
</script>

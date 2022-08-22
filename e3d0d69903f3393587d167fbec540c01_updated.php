<?php
if (file_exists('assets/init.php')) {
    require 'assets/init.php';
} else {
    die('Please put this file in the home directory !');
}
ini_set('max_execution_time', 0);
function check_($check)
{
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
    function Wo_UpdateLangs($lang, $key, $value)
    {
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
            $lang_update_queries[] = Wo_UpdateLangs($value, 'yoomoney', 'yoomoney.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'iyzipay', 'iyzipay.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'securionpay', 'securionpay.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'authorize', 'Authorize.net.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'product_sale', 'بيع المنتج');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'apps', 'تطبيقات');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'read_more_text', 'اقرأ أكثر');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'read_less_text', 'أقرأ أقل');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_messenger_apps', 'الحصول على رسول');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_mobile_apps', 'الحصول على تطبيقات الجوال');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wow_remember', 'تذكر؟');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_welcome_back', 'مرحبا بعودتك!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_remmember_subtitle', 'دعنا نعود وحاول مرة أخرى.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_have_an_account', 'هل لديك حساب؟');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_sub_title', 'إذا كان لديك حساب.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_signup', 'اشتراك');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_signin', 'تسجيل الدخول');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_secured', 'مضمون');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_fast', 'بسرعة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_easy', 'سهل');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_discover', 'يكتشف');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_now', 'سجل الان');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_now_subtitle', 'في حين أن الأصدقاء الجدد ينتظرونك.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_register', 'يسجل');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_remain_social_and_simple', 'تبقى الاجتماعية وبسيطة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_first_title', 'الوقت ليكون اجتماعيا، الذهاب الاجتماعية');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_first_subtitle', 'يمكننا ربطك بالعالم من مليون شخص. ');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_second_title', 'العثور على الفضاء الحقيقي الخاص بك');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_second_subtitle', 'تشعر بالتجربة التي لا مثيل لها.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_third_title', 'تقاسم رؤيتك');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_third_subtitle', 'يكون تخيل، كن فني ودعونا نخريك.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_fourth_title', 'دعونا نجاح باهر وجودك عبر الإنترنت');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_fourth_subtitle', 'دعونا نساعدك على تعزيز الوجود الاجتماعي الخاص بك.');
        } else if ($value == 'dutch') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'yoomoney', 'Yoomoney');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'iyzipay', 'Iyzipay');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'securionpay', 'Securionpay');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'authorize', 'Autoriseer.net');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'product_sale', 'Productverkoop');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'apps', 'Apps');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'read_more_text', 'Lees verder');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'read_less_text', 'Lees minder');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_messenger_apps', 'Krijg Messenger');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_mobile_apps', 'Krijg mobiele apps');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wow_remember', 'Onthouden?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_welcome_back', 'Welkom terug!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_remmember_subtitle', 'Laten we teruggaan en het opnieuw proberen.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_have_an_account', 'Een account hebben?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_sub_title', 'Als u een account hebt.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_signup', 'Aanmelden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_signin', 'Aanmelden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_secured', 'Beveiligd');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_fast', 'Snel');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_easy', 'Eenvoudig');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_discover', 'Ontdekken');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_now', 'Registreer nu');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_now_subtitle', 'Terwijl nieuwe vrienden op je wachten.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_register', 'Register');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_remain_social_and_simple', 'Sociaal en eenvoudig blijven');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_first_title', 'Tijd om sociaal te zijn, ga sociaal');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_first_subtitle', 'We kunnen u verbinden met de wereld van miljoen mensen. ');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_second_title', 'Vind je ware ruimte');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_second_subtitle', 'Voel de ongeëvenaarde ervaring die we bezitten.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_third_title', 'Uw visie delen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_third_subtitle', 'Stel je voor, wees artistiek en laten we beginnen.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_fourth_title', 'Laten we uw online aanwezigheid wow');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_fourth_subtitle', 'Laat ons u helpen om uw sociale aanwezigheid te verbeteren.');
        } else if ($value == 'french') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'yoomoney', 'Maillot');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'iyzipay', 'Iyzipay');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'securionpay', 'SecurionPay');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'authorize', 'Autoriser.net');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'product_sale', 'Vente de produits');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'apps', 'applications');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'read_more_text', 'Lire la suite');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'read_less_text', 'Lire moins');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_messenger_apps', 'Faire des messagers');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_mobile_apps', 'Obtenez des applications mobiles');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wow_remember', 'Rappelles toi?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_welcome_back', 'Content de te revoir!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_remmember_subtitle', 'Revenons en arrière et réessayons.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_have_an_account', 'Avoir un compte?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_sub_title', 'Si vous avez un compte.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_signup', 'S\'inscrire');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_signin', 'S\'identifier');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_secured', 'Sécurisé');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_fast', 'Vite');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_easy', 'Facile');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_discover', 'Découvrir');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_now', 'S\'inscrire maintenant');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_now_subtitle', 'Alors que de nouveaux amis vous attendent.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_register', 'S\'inscrire');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_remain_social_and_simple', 'Rester social et simple');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_first_title', 'Il est temps d\'être social, aller social');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_first_subtitle', 'Nous pouvons vous connecter au monde de millions de personnes. ');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_second_title', 'Trouvez votre véritable espace');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_second_subtitle', 'Sentez l\'expérience inégalable que nous possédons.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_third_title', 'Partage de votre vision');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_third_subtitle', 'Soyez imaginez, soyez artistique et engagez-vous.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_fourth_title', 'Wow wow votre présence en ligne');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_fourth_subtitle', 'Laissez-nous vous aider à améliorer votre présence sociale.');
        } else if ($value == 'german') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'yoomoney', 'Yoomoney.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'iyzipay', 'IYZIPAY.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'securionpay', 'SecurionPay.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'authorize', 'Autorisieren.net.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'product_sale', 'Produktverkauf.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'apps', 'Apps');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'read_more_text', 'Weiterlesen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'read_less_text', 'Lese weniger');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_messenger_apps', 'Get Messenger');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_mobile_apps', 'Holen Sie sich mobile Apps.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wow_remember', 'Erinnere dich?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_welcome_back', 'Willkommen zurück!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_remmember_subtitle', 'Lass uns zurückgehen und es noch einmal versuchen.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_have_an_account', 'Ein Konto haben?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_sub_title', 'Wenn Sie ein Konto haben.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_signup', 'Anmelden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_signin', 'Anmelden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_secured', 'Gesichert');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_fast', 'Schnell');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_easy', 'Leicht');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_discover', 'Entdecken');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_now', 'Jetzt registrieren');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_now_subtitle', 'Während neue Freunde auf dich warten.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_register', 'Registrieren');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_remain_social_and_simple', 'Sozial und einfach bleiben');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_first_title', 'Zeit, um sozial zu sein, gehen Sie sozial');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_first_subtitle', 'Wir können Sie an die Welt der Millionen Menschen verbinden. ');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_second_title', 'Finden Sie Ihren wahren Raum');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_second_subtitle', 'Spüren Sie die unübertroffene Erfahrung, die wir besitzen.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_third_title', 'Ihre Vision teilen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_third_subtitle', 'Stellen Sie sich vor, seien Sie künstlerisch und lasst uns eingehen.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_fourth_title', 'Wow, wow deine Online-Präsenz');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_fourth_subtitle', 'Lassen Sie uns Ihnen helfen, Ihre soziale Präsenz zu verbessern.');
        } else if ($value == 'russian') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'yoomoney', 'Юмини');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'iyzipay', 'Iyzipay');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'securionpay', 'Securionpay.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'authorize', 'Auralize.net.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'product_sale', 'Продажа продукта');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'apps', 'Программы');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'read_more_text', 'Подробнее');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'read_less_text', 'Читать меньше');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_messenger_apps', 'Получить мессенджер');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_mobile_apps', 'Получить мобильные приложения');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wow_remember', 'Запомнить?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_welcome_back', 'Добро пожаловать!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_remmember_subtitle', 'Давайте вернемся и попробуем еще раз.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_have_an_account', 'Иметь аккаунт?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_sub_title', 'Если у вас есть аккаунт.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_signup', 'Зарегистрироваться');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_signin', 'Войти');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_secured', 'Закреплен');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_fast', 'Быстро');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_easy', 'Легкий');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_discover', 'Обнаружить');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_now', 'Зарегистрируйтесь сейчас');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_now_subtitle', 'Пока новых друзей ждут вас.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_register', 'регистр');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_remain_social_and_simple', 'Оставаться социальными и простыми');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_first_title', 'Время быть социальным, иди социальным');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_first_subtitle', 'Мы можем подключить вас к миру миллиона человек. ');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_second_title', 'Найти свое настоящее пространство');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_second_subtitle', 'Почувствуйте непревзойденный опыт, который мы обладаем.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_third_title', 'Распределение вашего видения');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_third_subtitle', 'Будьте представить, будьте хуже и давайте участвовать.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_fourth_title', 'Давайте вашим вашим онлайн присутствием');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_fourth_subtitle', 'Позвольте нам помочь вам улучшить ваше социальное присутствие.');
        } else if ($value == 'spanish') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'yoomoney', 'Eoomoney');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'iyzipay', 'IYZIPAY');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'securionpay', 'PAYO DE SECURION');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'authorize', 'Autorize.net');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'product_sale', 'Venta de productos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'apps', 'Apps');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'read_more_text', 'Leer más');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'read_less_text', 'Leer menos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_messenger_apps', 'Obtener mensajero');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_mobile_apps', 'Obtener aplicaciones móviles');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wow_remember', '¿Recordar?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_welcome_back', '¡Bienvenido de nuevo!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_remmember_subtitle', 'Volvamos e intentemos de nuevo.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_have_an_account', '¿Tener una cuenta?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_sub_title', 'Si tienes una cuenta.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_signup', 'Inscribirse');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_signin', 'Iniciar sesión');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_secured', 'Asegurado');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_fast', 'Rápido');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_easy', 'Fácil');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_discover', 'Descubrir');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_now', 'Regístrate ahora');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_now_subtitle', 'Mientras que los nuevos amigos te están esperando.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_register', 'Registrarse');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_remain_social_and_simple', 'Permanecer social y simple');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_first_title', 'Es hora de ser social, ir social.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_first_subtitle', 'Podemos conectarte al mundo de millones de personas. ');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_second_title', 'Encuentra tu verdadero espacio');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_second_subtitle', 'Siente la experiencia inigualable que poseemos.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_third_title', 'Compartiendo tu visión');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_third_subtitle', 'Se imagine, sea artístico y vamos a participar.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_fourth_title', 'Vamos a wow tu presencia en línea');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_fourth_subtitle', 'Permítanos ayudarlo a mejorar su presencia social.');
        } else if ($value == 'turkish') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'yoomoney', 'Yoomoney');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'iyzipay', 'İyzipay');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'securionpay', 'Securionpay');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'authorize', 'Authorize.net');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'product_sale', 'Ürün satışı');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'apps', 'Apps');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'read_more_text', 'Daha fazla oku');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'read_less_text', 'Az oku');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_messenger_apps', 'Haberci almak');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_mobile_apps', 'MOBİL APPS GET');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wow_remember', 'Unutma?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_welcome_back', 'Tekrar hoşgeldiniz!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_remmember_subtitle', 'Geri dönelim ve tekrar deneyin.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_have_an_account', 'Hesabın var mı?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_sub_title', 'Eğer bir hesabınız varsa.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_signup', 'Üye olmak');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_signin', 'Kayıt olmak');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_secured', 'Güvence altına almak');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_fast', 'Hızlı');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_easy', 'Kolay');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_discover', 'Keşfetmek');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_now', 'Şimdi üye Ol');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_now_subtitle', 'Yeni arkadaşlar seni beklerken.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_register', 'Kayıt ol');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_remain_social_and_simple', 'Sosyal ve basit kal');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_first_title', 'Sosyal olma zamanı, sosyal değil');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_first_subtitle', 'Sizi milyonlarca insan dünyasına bağlayabiliriz. ');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_second_title', 'Gerçek alanınızı bulun');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_second_subtitle', 'Sahip olduğumuz eşsiz deneyimi hissedin.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_third_title', 'Vizyonunuzu paylaşma');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_third_subtitle', 'Hayal edin, sanatsal olun ve meşgul olun.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_fourth_title', 'Çevrimiçi varlığını vayelim');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_fourth_subtitle', 'Sosyal varlığınızı arttırmanıza yardımcı olalım.');
        } else if ($value == 'portuguese') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'yoomoney', 'Yoomoney.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'iyzipay', 'Iyzipay.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'securionpay', 'Securionpay.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'authorize', 'Autorize.net.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'product_sale', 'Venda do produto');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'apps', 'Aplicativos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'read_more_text', 'consulte Mais informação');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'read_less_text', 'Leia menos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_messenger_apps', 'Obter mensageiro');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_mobile_apps', 'Obtenha aplicativos móveis');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wow_remember', 'Lembrar?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_welcome_back', 'Bem vindo de volta!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_remmember_subtitle', 'Vamos voltar e tentar novamente.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_have_an_account', 'Ter uma conta?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_sub_title', 'Se você tem uma conta.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_signup', 'Inscrever-se');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_signin', 'Entrar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_secured', 'Protegido');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_fast', 'Rápido');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_easy', 'Fácil');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_discover', 'Descobrir');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_now', 'Registrar agora');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_now_subtitle', 'Enquanto novos amigos estão esperando por você.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_register', 'Registro');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_remain_social_and_simple', 'Permaneça social e simples');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_first_title', 'Hora de ser social, ir social');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_first_subtitle', 'Podemos conectá-lo ao mundo de milhões de pessoas. ');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_second_title', 'Encontre seu verdadeiro espaço');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_second_subtitle', 'Sinta a experiência inigualável que possuímos.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_third_title', 'Compartilhando sua visão');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_third_subtitle', 'Seja imagino, seja artístico e vamos nos envolver.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_fourth_title', 'Vamos wow sua presença online');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_fourth_subtitle', 'Vamos ajudá-lo a melhorar sua presença social.');
        } else if ($value == 'italian') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'yoomoney', 'Yoomone.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'iyzipay', 'Iyzipay.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'securionpay', 'SecurionPay.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'authorize', 'Autorize.net.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'product_sale', 'Vendita del prodotto');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'apps', 'Apps.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'read_more_text', 'Per saperne di più');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'read_less_text', 'Leggi di meno');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_messenger_apps', 'Get Messenger.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_mobile_apps', 'Ottieni app mobili');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wow_remember', 'Ricordare?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_welcome_back', 'Bentornato!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_remmember_subtitle', 'Torniamo indietro e riproviamo.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_have_an_account', 'Avere un conto?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_sub_title', 'Se hai un account.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_signup', 'Iscrizione');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_signin', 'Registrazione');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_secured', 'Assicurato');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_fast', 'Veloce');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_easy', 'Facile');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_discover', 'Scoprire');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_now', 'Iscriviti ora');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_now_subtitle', 'Mentre i nuovi amici ti stanno aspettando.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_register', 'Registrati');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_remain_social_and_simple', 'Rimanere sociale e semplice');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_first_title', 'È ora di essere sociale, vai sociale');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_first_subtitle', 'Possiamo collegarti al mondo di milioni di persone. ');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_second_title', 'Trova il tuo vero spazio');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_second_subtitle', 'Senti l\'esperienza ineguagliabile che possediamo.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_third_title', 'Condividere la tua visione');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_third_subtitle', 'Sii immaginare, essere artistico e ci impegniamo.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_fourth_title', 'Wow wow la tua presenza online');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_fourth_subtitle', 'Ti aiutiamo a migliorare la tua presenza sociale.');
        } else if ($value == 'english') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'yoomoney', 'Yoomoney');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'iyzipay', 'Iyzipay');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'securionpay', 'SecurionPay');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'authorize', 'Authorize.net');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'product_sale', 'Product sale');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'apps', 'Apps');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'read_more_text', 'Read More');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'read_less_text', 'Read Less');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_messenger_apps', 'Get Messenger');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_mobile_apps', 'Get Mobile Apps');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wow_remember', 'Remmember?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_welcome_back', 'Welcome Back!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_remmember_subtitle', 'Let’s go back and try again.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_have_an_account', 'Have an account?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_sub_title', 'If you have an account.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_signup', 'Sign up');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_signin', 'Sign In');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_secured', 'Secured');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_fast', 'Fast');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_easy', 'Easy');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_discover', 'Discover');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_now', 'Register Now');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_now_subtitle', 'While new friends are waiting for you.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_register', 'Register');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_remain_social_and_simple', 'Remain social and simple');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_first_title', 'Time to be Social, Go social');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_first_subtitle', 'We can connect you to the world of million people. Let’s connect');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_second_title', 'Find your true space');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_second_subtitle', 'Feel the unmatchable experience we possess.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_third_title', 'Sharing Your Vision');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_third_subtitle', 'Be Imagine, Be Artistic And Let’s Engage.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_fourth_title', 'Let’s Wow your online presence');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_fourth_subtitle', 'Let us help you to enhance your social presence.');
        } else if ($value != 'english') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'yoomoney', 'Yoomoney');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'iyzipay', 'Iyzipay');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'securionpay', 'SecurionPay');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'authorize', 'Authorize.net');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'product_sale', 'Product sale');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'apps', 'Apps');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'read_more_text', 'Read More');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'read_less_text', 'Read Less');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_messenger_apps', 'Get Messenger');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'get_mobile_apps', 'Get Mobile Apps');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wow_remember', 'Remmember?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_welcome_back', 'Welcome Back!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_remmember_subtitle', 'Let’s go back and try again.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_have_an_account', 'Have an account?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_sub_title', 'If you have an account.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_signup', 'Sign up');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_signin', 'Sign In');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_secured', 'Secured');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_fast', 'Fast');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_easy', 'Easy');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_feature_discover', 'Discover');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_now', 'Register Now');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme3_register_now_subtitle', 'While new friends are waiting for you.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_register', 'Register');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_remain_social_and_simple', 'Remain social and simple');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_first_title', 'Time to be Social, Go social');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_first_subtitle', 'We can connect you to the world of million people. Let’s connect');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_second_title', 'Find your true space');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_second_subtitle', 'Feel the unmatchable experience we possess.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_third_title', 'Sharing Your Vision');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_third_subtitle', 'Be Imagine, Be Artistic And Let’s Engage.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_fourth_title', 'Let’s Wow your online presence');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'theme4_section2_fourth_subtitle', 'Let us help you to enhance your social presence.');
        }
    }
    if (!empty($lang_update_queries)) {
        foreach ($lang_update_queries as $key => $query) {
            $sql = mysqli_query($sqlConnect, $query);
        }

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
                     <h2 class="light">Update to v4.0.1</span></h2>
                     <div class="setting-well">
                        <h4>Changelog</h4>
                        <ul class="wo_update_changelog">
                          <li> [Added] audio player for mp3 files.</li>
                          <li> [Added] yandex API for maps. </li>
                          <li> [Added] yandex payment method.</li>
                          <li> [Added] iyzipay payment method.</li>
                          <li> [Added] securionpay payment method.</li>
                          <li> [Added] authorize.Net payment method. </li>
                          <li> [Added] ok.ru social login.</li>
                          <li> [Added] new APIs in developers page: get_pages, get_groups, get_products, get_followers, get_following, get_friends</li>
                          <li> [Added] mobile apps links in welcome page and sidebar, managed from admin panel -> website information.</li>
                          <li> [Updated] design in few sections.</li>
                          <li> [Updated] documentation & FAQs: <a href="https://docs.wowonder.com/" target="_blank">https://docs.wowonder.com/</a> .</li>
                          <li> [Fixed] price not showing in products.</li>
                          <li> [Fixed] links not working on profile page.</li>
                          <li> [Fixed] ajax load in few pages.</li>
                          <li> [Fixed] broken images when importing a link.</li>
                          <li> [Fixed] add as familly system.</li>
                          <li> [Fixed] XSS vulnerability.</li>
                          <li> [Fixed] 10+ more minor bugs.</li>
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
    "UPDATE `Wo_Config` SET `value` = '4.0.1' WHERE `name` = 'version';",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'yandex_map', '0');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'yandex_map_api', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'yoomoney_payment', '0');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'yoomoney_wallet_id', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'yoomoney_notifications_secret', '');",
    "ALTER TABLE `Wo_Users` ADD `yoomoney_hash` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `coinbase_code`, ADD INDEX (`yoomoney_hash`);",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'iyzipay_payment', '0');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'iyzipay_mode', '1');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'iyzipay_key', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'iyzipay_secret_key', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'iyzipay_buyer_id', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'iyzipay_buyer_name', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'iyzipay_buyer_surname', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'iyzipay_buyer_gsm_number', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'iyzipay_buyer_email', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'iyzipay_identity_number', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'iyzipay_address', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'iyzipay_city', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'iyzipay_country', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'iyzipay_zip', '');",
    "ALTER TABLE `Wo_Users` ADD `ConversationId` INT(20) NOT NULL DEFAULT '0' AFTER `yoomoney_hash`, ADD INDEX (`ConversationId`);",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'securionpay_payment', '0');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'securionpay_public_key', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'securionpay_secret_key', '');",
    "ALTER TABLE `Wo_Users` ADD `securionpay_key` VARCHAR(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `ConversationId`, ADD INDEX (`securionpay_key`);",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'authorize_payment', '0');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'authorize_test_mode', 'SANDBOX');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'authorize_login_id', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'authorize_transaction_key', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'OkLogin', '0');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'OkAppId', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'OkAppPublickey', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'OkAppSecretkey', '');",
    "ALTER TABLE `Wo_Users` ADD `okru` VARCHAR(30) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `mailru`;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'native_android_messenger_url', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'native_android_timeline_url', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'native_ios_messenger_url', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'native_ios_timeline_url', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'native_windows_messenger_url', '');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'yoomoney');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'iyzipay');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'securionpay');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'authorize');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'product_sale');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'apps');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'read_more_text');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'read_less_text');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'get_messenger_apps');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'get_mobile_apps');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'wow_remember');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'theme3_welcome_back');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'theme3_remmember_subtitle');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'theme3_have_an_account');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'theme3_register_sub_title');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'theme3_signup');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'theme3_signin');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'theme3_feature_secured');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'theme3_feature_fast');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'theme3_feature_easy');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'theme3_feature_discover');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'theme3_register_now');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'theme3_register_now_subtitle');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'theme4_register');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'theme4_remain_social_and_simple');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'theme4_section2_first_title');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'theme4_section2_first_subtitle');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'theme4_section2_second_title');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'theme4_section2_second_subtitle');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'theme4_section2_third_title');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'theme4_section2_third_subtitle');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'theme4_section2_fourth_title');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'theme4_section2_fourth_subtitle');",
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

<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '{{ config('services.facebook.pixel_id') }}', {<?php
  if ($user) {
    $firstName = strtolower($user->first_name);
    $lastName = strtolower($user->last_name);
    $firstGroup = $user->myGroups->first();
    $phone = '';
    $city = '';

    if ($firstGroup and $firstGroup->phone) {
        $phone = str_replace('+', '', $firstGroup->phone);
        $phone = str_replace('-', '', $phone);
        $phone = str_replace(' ', '', $phone);
    }

    if ($user->location) {
        $location = json_decode($user->location);

        if ($location->city) {
          $city = str_replace(' ', '', $location->city);
          $city = strtolower($city);
        }
    }

    echo "em: '{$user->email}',";
    echo "fn: '{$firstName}',";
    echo "ln: '{$lastName}',";
    echo "ph: '{$phone}',";
    echo "ct: '{$city}'";

    unset($firstName, $lastName, $firstGroup, $phone, $city, $location);
  }
?>});
fbq('track', 'PageView');
</script>
<!-- End Facebook Pixel Code -->
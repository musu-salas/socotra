@if(env('APP_ENV') === 'production')
  <script>
    window.ga=function(){ga.q.push(arguments)};ga.q=[];ga.l=+new Date;ga('create','UA-52694054-1','auto');ga('send','pageview')
  </script>
  <script src="https://www.google-analytics.com/analytics.js" async defer></script>
@endif
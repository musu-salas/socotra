'use strict';

// import Authenticate from './controllers/authenticate';
// import Facebook from './controllers/facebook';
import Navigo from 'navigo';

const router = new Navigo(null, false);

router
  .on(() => {
    // Home page
  })

  /*.on('socialize/facebook', Facebook.socialize)

  .on('login', Authenticate.login)

  .on('password/reset', Authenticate.password)*/

  .resolve();

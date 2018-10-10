#!/usr/bin/env node

const chalk = require('chalk');
const output = require('./output');
const emoji = require('node-emoji');
const {exec} = require('promisify-child-process');
const {execSync} = require('child_process');
const files = require('./files');
const ora = require('ora');
const prompt = require('prompt-sync')();

const createDatabase = async(options) => {
  const create = `mysql -u ${options.dbRootUser} --password=${options.dbRootPass} -e "CREATE DATABASE IF NOT EXISTS ${options.dbName}"`;
  const grant = `mysql -u ${options.dbRootUser} --password=${options.dbRootPass} -e "GRANT ALL PRIVILEGES ON ${options.dbName}.* TO ${options.dbUser}@localhost IDENTIFIED BY 'wp';"`;

  return new Promise((resolve, reject) => {
    exec(create).then(() => {
      exec(grant).then(() => {
        resolve(true);
      }).catch((errGrant) => {
        reject(errGrant);
      });
    }).catch((errCreate) => {
      reject(errCreate);
    });
  });
};

/**
 * Outputs WordPress login info.
 *
 * @param string url   Website's local URL.
 * @param string user  WordPress user.
 * @param string pass  WordPress user's pass.
 */
const outputWPLoginInfo = (url, user, pass) => {
  console.log(`${emoji.get('tada')} Your login info: ${emoji.get('tada')}`);
  console.log('-------------------');
  console.log(`${emoji.get('earth_africa')} Login: ${chalk.green(url)}${chalk.green('/wp-admin')}`);
  console.log(`${emoji.get('sunglasses')} User: ${chalk.green(user)}`);
  console.log(`${emoji.get('lock')} Pass: ${chalk.green(pass)}`);
};

/**
 * Outputs intro for setting up WordPress
 */
exports.intro = (devUrl) => {
  console.log('');
  console.log(chalk.red('-----------------------------------------------------------------'));
  console.log('');
  console.log('    Congratulations!');
  console.log('');
  console.log('    Your project is setup and ALMOST ready to use,');
  console.log(`    all we need to do now is ${chalk.bgGreen.black('setup WordPress')}.`);
  console.log('');
  console.log('    You can set it up manually with the usual WordPress');
  console.log('    setup configuration wizard by going to your local');
  console.log(`    'dev url: ${chalk.green(devUrl)}`);
  console.log('');
  console.log('    However, we might be able to do it for you');
  console.log('    depending on your local dev environment...');
  console.log('');
  console.log(chalk.cyan('    Options:'));
  console.log('    1) Vagrant (VVV, Scotch box, etc)');
  console.log('    2) Custom local development server');
  console.log('    3) Thx but no thx, I\'ll setup WordPress manually');
  console.log('');
};

exports.selectEnv = () => {

  // Verify option
  let isValid = true;
  do {
    const selectedDevEnv = prompt('    Select option: ');
    console.log('');
    switch (selectedDevEnv) {
      case '1':
        isValid = true;
        exports.vvv();
        break;
      case '2':
        isValid = true;
        exports.custom();
        break;
      case '3':
        isValid = true;
        exports.manual();
        break;
      case 'exit':
        process.exit();
        break;
      default:
        isValid = false;
        output.error('Please input the number corresponding to your desired choice.');
    }
  } while (!isValid);
};

/**
 * Prompts the user for database name and returns it
 *
 * @return string
 */
const promptDatabase = () => output.prompt({
  label: 'Please enter database name (make sure it exists and is empty):',
  prompt: `${emoji.get('school_satchel')} Database name: `,
  error: 'Database name cannot be empty',
  required: true,
}).trim();

/**
 * Prompts the user for database user and returns it
 *
 * @return string
 */
const promptDatabaseUser = () => output.prompt({
  label: 'Please enter database user:',
  prompt: 'Database user: ',
  error: 'Database user cannot be empty',
  required: true,
}).trim();

/**
 * Prompts the user for database pass and returns it
 *
 * @return string
 */
const promptDatabasePass = () => output.prompt({
  label: 'Please enter database user:',
  prompt: 'Database user: ',
  error: 'Database user cannot be empty',
  required: true,
});

/**
 * Prompts the user for database host and returns it
 *
 * @return string
 */
const promptDatabaseHost = () => output.prompt({
  label: 'Please enter database host (usually "localhost"):',
  prompt: 'Database host: ',
  error: 'Database host cannot be empty',
  required: true,
});

/**
 * Prompts the user for database host and returns it
 *
 * @return string
 */
const promptDatabasePrefix = () => output.prompt({
  label: 'Please enter database prefix (usually "wp"):',
  prompt: 'Database prefix: ',
  error: 'Database prefix cannot be empty',
  required: true,
});

/**
 * Prompts the user for database name and returns it
 *
 * @return string
 */
const promptVmDir = () => output.prompt({
  label: '1. Please enter your project\'s \'vm_dir\' Vagrant path (by default in VVV it\'s /srv/www/PROJECTNAME/public_html):',
  prompt: `${emoji.get('trolleybus')} Path: `,
  error: 'vm_dir cannot be empty.',
  required: true,
}).trim().replace(/\/+$/, '/');

/**
 * Creates a fairly secure WordPress default password
 */
const randomWpPass = () => {
  const randomPass = Math.random().toString(36).slice(-14);
  return randomPass;
};

/**
 * Outputs intro for specific development setups
 */
const specificSetupInfo = (name) => {
  console.log(chalk.red('---------------------------------------------------------------'));
  console.log('');
  output.dim('   Let\'s setup your WordPress installation for');
  console.log(`   ${chalk.bgGreen.black(name)}...`);
  console.log('');
};

/**
 * Setup WordPress for users using Varying Vagrant Vagrants
 */
exports.vvv = async() => {

  specificSetupInfo('Varying Vagrant Vagrants');

  // ------------------------------
  // Prompting
  // ------------------------------

  const vmdir = promptVmDir();
  const wpInfo = {
    dbName: promptDatabase(),
    dbUser: 'wp',
    dbPass: 'wp',
    siteUrl: files.readManifest('url'),
    siteName: 'WP_Boilerplate',
    user: 'Admin',
    pass: randomWpPass(),
    email: files.readManifest('email'),
    themePackage: files.readManifest('package'),
  };

  // ==============================
  //
  //  Verify everything
  //
  // ==============================

  output.dim('    Great, let\'s get started...');
  output.dim('');

  // ------------------------------
  //  1. Verify vm_dir
  // ------------------------------

  const spinnerTestFolder = ora('1. Verifying vm_dir path').start();
  await exec(`vagrant ssh -- -t 'cd ${vmdir};'`).then(() => {
  }).catch((error) => {
    spinnerTestFolder.fail(`${spinnerTestFolder.text} - Unable to find vm_dir ${vmdir}\n\n${error}`);
    process.exit();
  });

  await exec(`vagrant ssh -- -t '
    cd ${vmdir};
    tail theme-manifest.json
  '`).then(() => {
    spinnerTestFolder.succeed();
  }).catch((error) => {
    spinnerTestFolder.fail(`${spinnerTestFolder.text} - Unable to verify wp-boilerplate installation in ${vmdir}\n\n${error}`);
    process.exit();
  });

  // -----------------
  //  2. Verify wp-cli
  // -----------------

  const spinnerWPCLI = ora('2. Checking if wp-cli works').start();
  await exec(`vagrant ssh -- -t '
      cd ${vmdir};
      wp --info
    '`).then(() => {
    spinnerWPCLI.succeed();
  }).catch((error) => {
    spinnerWPCLI.fail(`${spinnerWPCLI.text}\n\n${error}`);
    process.exit();
  });

  // -----------------
  //  3. Create wp-config
  // -----------------

  const spinnerWpConfig = ora('3. Creating wp-config.php').start();
  await exec(`vagrant ssh -- -t '
      cd ${vmdir};
      wp config create --dbname=${wpInfo.dbName} --dbuser=${wpInfo.dbUser} --dbpass=${wpInfo.dbPass};
    '`).then(() => {
    spinnerWpConfig.succeed();
  }).catch((error) => {
    spinnerWpConfig.fail(`${spinnerWpConfig.text}\n\n${error}`);
    process.exit();
  });

  // ------------------------------
  //  4. Create database if it doesn't exist
  // ------------------------------

  const spinnerCreateDB = ora('4. Creating database if it doesn\'t exist').start();
  await exec(`vagrant ssh -- -t '
    cd ${vmdir};
    wp db create;
    '`).then(() => {
    spinnerCreateDB.succeed();
  }).catch((error) => {
    spinnerCreateDB.succeed();
  });

  // ------------------------------
  //  5. Install WordPress
  // ------------------------------

  const spinnerInstallWP = ora('5. Installing WordPress Core').start();
  await exec(`vagrant ssh -- -t '
    cd ${vmdir};
    wp core install --url=${wpInfo.siteUrl} --title=${wpInfo.siteName} --admin_user=${wpInfo.user} --admin_password=${wpInfo.pass} --admin_email=${wpInfo.email};
    '`).then(() => {
    spinnerInstallWP.succeed();
  }).catch((error) => {
    spinnerInstallWP.fail(`${spinnerInstallWP.text}\n\n${error}`);
    process.exit();
  });

  // ------------------------------
  //  6. Building Assets
  // ------------------------------

  const spinnerBuildAssets = ora('6. Building assets').start();
  await exec('npm run build').then(() => {
    spinnerBuildAssets.succeed();
  }).catch((error) => {
    spinnerBuildAssets.fail(`${spinnerBuildAssets.text}\n\n${error}`);
    process.exit();
  });

  // ------------------------------
  //  7. Activate Theme
  // ------------------------------

  const spinnerActivateTheme = ora('7. Activating theme').start();
  await exec(`vagrant ssh -- -t '
    cd ${vmdir};
    wp theme activate ${wpInfo.themePackage};
    '`).then(() => {
    spinnerActivateTheme.succeed();
    console.log('');
    output.success('Done! ');
    console.log('');
    outputWPLoginInfo(wpInfo.siteUrl, wpInfo.user, wpInfo.pass);
  }).catch((error) => {
    spinnerActivateTheme.fail(`${spinnerActivateTheme.text}\n\n${error}`);
    outputWPLoginInfo(wpInfo.siteUrl, wpInfo.user, wpInfo.pass);
    process.exit();
  });
};

/**
 * Setup WordPress for users using custom development setup (manually installed php / mysql)
 */
exports.custom = async() => {
  specificSetupInfo('Custom Local Development Server');

  // ------------------------------
  //  Prompting
  // ------------------------------

  const wpInfo = {
    dbName: promptDatabase(),
    dbUser: promptDatabaseUser(),
    dbPass: promptDatabasePass(),
    dbHost: promptDatabaseHost(),
    dbPrefix: promptDatabasePrefix(),
    siteUrl: files.readManifest('url'),
    siteName: 'WP_Boilerplate',
    user: 'Admin',
    pass: randomWpPass(),
    email: files.readManifest('email'),
    themePackage: files.readManifest('package'),
  };

  // ------------------------------
  //  1. Try creating wp-config
  // ------------------------------

  const spinnerWpConfig = ora('1. Creating wp-config').start();
  await exec(`wp config create --dbname=${wpInfo.dbName} --dbuser=${wpInfo.dbUser} --dbpass=${wpInfo.dbPass} --dbhost=${wpInfo.dbHost} --dbprefix=${wpInfo.dbPrefix}`).then(() => {
    spinnerWpConfig.succeed();
  }).catch((error) => {
    spinnerWpConfig.fail(`${spinnerWpConfig.text}\n\n${error}`);
    process.exit();
  });

  // ------------------------------
  //  2. Try installing WordPress core
  // ------------------------------

  const spinnerWpCoreInstall = ora('2. Installing WordPress core').start();
  await exec(`wp core install --url=${wpInfo.siteUrl} --title=${wpInfo.siteName} --admin_user=${wpInfo.user} --admin_password=${wpInfo.pass} --admin_email=${wpInfo.email}`).then(() => {
    spinnerWpCoreInstall.succeed();
  }).catch((error) => {
    spinnerWpCoreInstall.fail(`${spinnerWpCoreInstall.text}\n\n${error}`);
    process.exit();
  });

  // ------------------------------
  //  3. Building Assets
  // ------------------------------

  const spinnerBuildAssets = ora('3. Building assets').start();
  await exec('npm run build').then(() => {
    spinnerBuildAssets.succeed();
  }).catch((error) => {
    spinnerBuildAssets.fail(`${spinnerBuildAssets.text}\n\n${error}`);
    process.exit();
  });

  // ------------------------------
  //  4. Activate Theme
  // ------------------------------

  const spinnerActivateTheme = ora('4. Activating theme').start();
  await exec(`wp theme activate ${wpInfo.themePackage}`).then(() => {
    spinnerActivateTheme.succeed();
    console.log('');
    output.success('Done! ');
    console.log('');
    outputWPLoginInfo(wpInfo.siteUrl, wpInfo.user, wpInfo.pass);
  }).catch((error) => {
    spinnerActivateTheme.fail(`${spinnerActivateTheme.text}\n\n${error}`);
    outputWPLoginInfo(wpInfo.siteUrl, wpInfo.user, wpInfo.pass);
    process.exit();
  });
};

/**
 * Message for users using something other than provided options.
 */
exports.else = () => {
  console.log('    In order to finish the installation manually,');
  console.log('    please go to your website\'s local dev url.');
};

/**
 * Message for users wanting to setup WordPress manually.
 */
exports.manual = () => {
  console.log('    In order to finish the installation manually,');
  console.log('    please go to your website\'s local dev url.');
};

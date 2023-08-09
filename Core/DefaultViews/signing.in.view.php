<?php
@session_start();


if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login-default-button'])){
   if(empty($_POST['username']) || empty($_POST['password'])){
       echo \Alerts\Alerts::alert('warning', "Fields are empty please fill up fields");
       goto out;
   }

   $data = [
           'mail'=>htmlspecialchars(strip_tags($_POST['username'])),
           'password'=>htmlspecialchars(strip_tags($_POST['password']))
   ];

   //log in below
    if(\FormViewCreation\Logging::signingIn($data['password'], ['mail'=>$data['mail']])){
        \GlobalsFunctions\Globals::redirect(\GlobalsFunctions\Globals::home());
    }else{
        echo \Alerts\Alerts::alert('danger', "Incorrect user input try again");
    }

   out:
}
?>
<div class="w-full max-w-xs" style="margin-top: 30%; margin-left: 10%;">
    <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4" method="POST" action="<?php echo \GlobalsFunctions\Globals::url(); ?>">
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                Username
            </label>
            <input name="username" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="username" type="text" placeholder="Username">
        </div>
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                Password
            </label>
            <input name="password" class="shadow appearance-none border border-red-500 rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" placeholder="******************">
            <p class="text-red-500 text-xs italic">Please choose a password.</p>
        </div>
        <div class="flex items-center justify-between">
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" name="login-default-button">
                Sign In
            </button>
            <a class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="reset-password">
                Forgot Password?
            </a>
        </div>
    </form>
    <p class="text-center text-gray-500 text-xs">
        &copy;2020 Acme Corp. All rights reserved.
    </p>
</div>

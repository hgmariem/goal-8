<?php 
namespace App\Repositories;

interface IUserRepository{
	
	public function register($user);
    public function login($request);
	public function forgotPwd($request);
	public function updatePwd($request);
	public function logout();

	public function getUserByEmail($email);
	public function getUserByToken($token);
	public function updateUserPwdByToken($token, $newPwd);
	public function update_profile($request, $user_id);
	public function Change_password($request, $user_id, $hashedPassword);
	public function unsubscribe_user($request, $user_id);
	public function getUserByParam($key, $value);

	// more
}
<?php

namespace app\models;

use Yii;

   /**
   * This is the model class for table "user".
   *
   * @property integer $id
   * @property string $username
   * @property string $password
   * @property string $fullname
   * @property string $type
   */

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /*public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;*/

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'fullname'], 'required'],
            [['username', 'password', 'fullname'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 1]
        ];
    }


    /* @inheritdoc
    */
   public function attributeLabels()
   {
       return [
           'id' => Yii::t('app', 'Id'),
           'username' => Yii::t('app', 'Username'),
           'password' => Yii::t('app', 'Password'),
           'fullname' => Yii::t('app', 'Full Name'),
           'type' => Yii::t('app', 'Type'),
       ];
   }

    /** INCLUDE USER LOGIN VALIDATION FUNCTIONS**/
    /**
   * @inheritdoc
   */
  public static function findIdentity($id)
  {
      return static::findOne($id);
  }

  /**
   * @inheritdoc
   */
  /* modified */
  public static function findIdentityByAccessToken($token, $type = null)
  {
        return static::findOne(['access_token' => $token]);
  }

  /* removed
  public static function findIdentityByAccessToken($token)
  {
      throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
  }
  */
  /**
   * Finds user by username
   *
   * @param  string      $username
   * @return static|null
   */
  public static function findByUsername($username)
  {
      return static::findOne(['username' => $username]);
  }

  /**
   * Finds user by password reset token
   *
   * @param  string      $token password reset token
   * @return static|null
   */
  public static function findByPasswordResetToken($token)
  {
      $expire = \Yii::$app->params['user.passwordResetTokenExpire'];
      $parts = explode('_', $token);
      $timestamp = (int) end($parts);
      if ($timestamp + $expire < time()) {
          // token expired
          return null;
      }

      return static::findOne([
          'password_reset_token' => $token
      ]);
  }

  /**
   * @inheritdoc
   */
  public function getId()
  {
      return $this->getPrimaryKey();
  }

  /**
   * @inheritdoc
   */
  public function getAuthKey()
  {
      return $this->auth_key;
  }

  /**
   * @inheritdoc
   */
  public function validateAuthKey($authKey)
  {
      return $this->getAuthKey() === $authKey;
  }

  /**
   * Validates password
   *
   * @param  string  $password password to validate
   * @return boolean if password provided is valid for current user
   */
  public function validatePassword($password)
  {
      //return $this->password === sha1($password);
      return $this->password === $password;
  }

  /**
   * Generates password hash from password and sets it to the model
   *
   * @param string $password
   */
  public function setPassword($password)
  {
      $this->password_hash = Security::generatePasswordHash($password);
  }

  /**
   * Generates "remember me" authentication key
   */
  public function generateAuthKey()
  {
      $this->auth_key = Security::generateRandomKey();
  }

  /**
   * Generates new password reset token
   */
  public function generatePasswordResetToken()
  {
      $this->password_reset_token = Security::generateRandomKey() . '_' . time();
  }

  /**
   * Removes password reset token
   */
  public function removePasswordResetToken()
  {
      $this->password_reset_token = null;
  }
  /** EXTENSION MOVIE **/

}

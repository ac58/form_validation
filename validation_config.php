<?php

class Validation_config
{
	/**
	 * $config = array(
	 * 		'rule name'=> array(
	 * 			'field_name' => array(
	 * 				validation rule...
	 * 			)
	 * 		)
	 * 	)
	 */
	public function load($target = NULL)
	{
		$config = array(
			'users'=>array(
				'user_name'=>array(
					'min'	  => 2,
					'max'	  => 32,
					'jp_name' => '名前'
				),
				'user_mail'=>array(
					'required'=> true,
					'max'	  => 128,
					'type'	  => 'email',
					'jp_name' => 'メールアドレス'
				),
				'user_tel'=>array(
					'min'	  => 8,
					'max'	  => 13,
					'type'	  => 'int',
					'jp_name' => '電話番号'
				),
			)
		);

		try {
			if($target)
			{
				if(array_key_exists($target, $config))
				{
					return $config[$target];
				}
				else
				{
					throw new Exception('Not found validation key');
				}
			}
			else
			{
				throw new Exception('Target has not been specified');
			}
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}
}
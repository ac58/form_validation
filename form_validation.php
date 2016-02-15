<?php
require_once 'validation_config.php';

class Form_validation
{
	private $validators = array(),
			$error_msg  = array();

	public function __construct($target)
	{
		$v_config = new Validation_config();
		$this->validators = $v_config->load($target);
	}

	public function get_rule()
	{
		return $this->validators;
	}

	public function set_rule($rule)
	{
		$this->validators = $rule;
	}

	public function get_error_msg()
	{
		return $this->error_msg;
	}

	public function run($inputs)
	{
		foreach($this->validators as $field_name=>$validat)
		{
			if(array_key_exists($field_name, $inputs))
			{
				foreach($validat as $key=>$value)
				{
					//min-length(Number of character)
					if($key == 'min')
					{
						if(mb_strlen($inputs[$field_name]) <= $value)
						{
							if(mb_strlen($inputs[$field_name]) == 0)
							{
								$this->error_msg[$field_name] = $validat['jp_name'] . 'は必須項目です';
							}
							else
							{
								$this->error_msg[$field_name] = $validat['jp_name'] . 'は' . $value . '文字以上で入力してください';
							}
							continue;
						}
					}
					//max-length(Number of character)
					if($key == 'max')
					{
						if(mb_strlen($inputs[$field_name]) >= $value)
						{
							$this->error_msg[$field_name] = $validat['jp_name'] . 'は' . $value . '文字以下で入力してください';
							continue;
						}
					}

					//int-below
					if($key == 'below')
					{
						if(mb_strlen($inputs[$field_name]) == 0) continue;
						if($inputs[$field_name] > $value)
						{
							$this->error_msg[$field_name] = $validat['jp_name'] . 'は' . $value . '以下の数値で入力してください';
							continue;
						}
					}
					//int-more
					if($key == 'more')
					{
						if(mb_strlen($inputs[$field_name]) == 0) continue;
						if($inputs[$field_name] < $value)
						{
							$this->error_msg[$field_name] = $validat['jp_name'] . 'は' . $value . '以上の数値で入力してください';
							continue;
						}
					}

					if($key == 'type')
					{
						if(mb_strlen($inputs[$field_name]) == 0) continue;

						if($value == 'int')
						{
							if(!preg_match('/^\-?[0-9]+$/', $inputs[$field_name]))
							{
								if(!isset($this->error_msg[$field_name])) $this->error_msg[$field_name] = $validat['jp_name'] . 'に' . '数字以外の文字が入力されています';
								continue;
							}
						}
						if($value == 'string')
						{
							if(!preg_match('/[^0-9]/', $inputs[$field_name]))
							{
								if(!isset($this->error_msg[$field_name])) $this->error_msg[$field_name] = $validat['jp_name'] . 'に' . '数字が入力されています';
								continue;
							}
						}
						if($value == 'passwd')
						{
							if(preg_match('/[^0-9a-zA-Z]/', $inputs[$field_name]))
							{
								if(!isset($this->error_msg[$field_name])) $this->error_msg[$field_name] = $validat['jp_name'] . 'に' . '英数字以外の文字が入力されています';
								continue;
							}
						}
						if($value == 'email')
						{
							if(!preg_match('/^[-+.\\w]+@[-a-z0-9]+(\\.[-a-z0-9]+)*\\.[a-z]{2,6}$/i', $inputs[$field_name]))
							{
								if(!isset($this->error_msg[$field_name])) $this->error_msg[$field_name] = 'メールアドレスの入力に誤りがあります';
								continue;
							}
						}
						if($value == 'match')
						{
							if($inputs[$validat['target']] != $inputs[$field_name])
							{
								if(!isset($this->error_msg[$field_name])) $this->error_msg[$field_name] = $this->validators[$field_name]['target_jp_name'] . 'と一致していません';
								continue;
							}
						}
					}
				}
			}
			else
			{
				if(isset($validat['required']))
				{
					$this->error_msg[$field_name] = $validat['jp_name'] . 'は必須項目です';
				}
			}
		}

		if(!count($this->error_msg)) return true;
		return false;
	}
}
<?php
/**
 * Fluxoft\Rebar\Container
 * 
 * Your basic dependency injection container class.
 * 
 * @author Joe Hart
 */
namespace Fluxoft\Rebar;

class Container implements \ArrayAccess {
	protected $values  = [];
	protected $objects = [];
	
	public function __isset($key) {
		return $this->offsetExists($key);
	}
	public function __get($key) {
		return $this->offsetGet($key);
	}
	public function __set($key, $value) {
		$this->offsetSet($key, $value);
	}
	public function __unset($key) {
		$this->offsetUnset($key);
	}
	
	// ArrayAccess
	public function offsetExists($offset) {
		return isset($this->values[$offset]);
	}
	public function offsetGet($offset) {
		if (!isset($this->values[$offset])) {
			throw new \InvalidArgumentException(sprintf('Value "%s" is not defined.', $offset));
		}
		if (is_callable($this->values[$offset])) {
			if ($this->objects[$offset]) {
				return $this->values[$offset];
			} else {
				$this->objects[$offset] = true;
				$this->values[$offset]  = $this->values[$offset]($this);
				return $this->values[$offset];
			}
		} else {
			return $this->values[$offset];
		}
	}
	public function offsetSet($offset, $value) {
		$this->objects[$offset] = false;
		$this->values[$offset]  = $value;
	}
	public function offsetUnset($offset) {
		unset($this->values[$offset]);
	}
}

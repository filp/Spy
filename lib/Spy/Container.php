<?php

/*
 * The MIT License
 * 
 * Copyright (c) 2011 Filipe Dobreira <http://github.com/FilipeD>
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy 
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR
 * IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace Spy;

/**
 * \Spy\Container
 *
 * @author  Filipe Dobreira <http://github.com/FilipeD>
 * @license The MIT License <http://www.opensource.org/licenses/mit-license.php>
 * @package Spy
 * @version 1
 */
class Container
{
	/**
	 * @access protected
	 * @var    array
	 */
	protected $injections;

	/**
	 * Registers a new injection, overriding any existing
	 * injections for the given identifier, if applicable.
	 *
	 * @access public
	 * @throws \Spy\Exception If not a valid $callable
	 * @param  string   $identifier
	 * @param  callable $callable
	 */
	public function set($identifier, $callable)
	{
		if(!is_callable($callable))
		{
			throw new Exception('Injection failed, second argument ($callable) must be a valid callable.');
		}

		$this->injections[$identifier] = $callable;
	}

	/**
	 * Instantiate an injectable by identifier. All methods
	 * besides the identifier that are passed to this method
	 * will in turn be passed down to the injected callable.
	 *
	 * @access public
	 * @throws \Spy\Exception If attempt to get unknown identifier
	 * @throws \Spy\Exception If unexpected callable return
	 * @param  string $identifier
	 * @param  mixed  ...
	 * @return object
	 */
	public function get($identifier)
	{
		if(!$this->has($identifier))
		{
			throw new Exception('Attempt to `get` unknown identifier.');
		}

		$args = array_slice(func_get_args(), 1);
		$callableReturn = call_user_func_array($this->injections[$className], $args);
		if(!is_object($callableReturn))
		{
			throw new Exception('Unexpected callable return, expected an object, got '
			                    . gettype($callableReturn) . ' instead.');
		}

		return $callableReturn;
	}

	/**
	 * Checks if this container has a registered injection
	 * for the given identifier.
	 *
	 * @access public
	 * @param  string $identifier
	 * @return bool
	 */
	public function has($identifier)
	{
		return isset($this->injections[$identifier]);
	}

	/**
	 * Removes a registered injection, by identifier,
	 * from this container.
	 *
	 * @access public
	 * @param  string $identifier
	 */
	public function remove($identifier)
	{
		unset($this->injections[$identifier]);
	}
}
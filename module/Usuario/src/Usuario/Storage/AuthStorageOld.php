<?php

use Zend\Authentication\Storage\StorageInterface;

/**
 * Description of AuthStorage
 *
 * @author CSING
 */
class AuthStorage implements StorageInterface {

    /**
     * Returns true if and only if storage is empty
     *
     * @throws \Zend\Authentication\Exception\ExceptionInterface
     *               If it is impossible to
     *               determine whether storage is empty
     * @return boolean
     */
    public function isEmpty() {
        /**
         * @todo implementation
         */
    }

    /**
     * Returns the contents of storage
     *
     * Behavior is undefined when storage is empty.
     *
     * @throws \Zend\Authentication\Exception\ExceptionInterface
     *               If reading contents from storage is impossible
     * @return mixed
     */
    public function read() {
        /**
         * @todo implementation
         */
    }

    /**
     * Writes $contents to storage
     *
     * @param  mixed $contents
     * @throws \Zend\Authentication\Exception\ExceptionInterface
     *               If writing $contents to storage is impossible
     * @return void
     */
    public function write($contents) {
        /**
         * @todo implementation
         */
    }

    /**
     * Clears contents from storage
     *
     * @throws \Zend\Authentication\Exception\ExceptionInterface
     *               If clearing contents from storage is impossible
     * @return void
     */
    public function clear() {
        /**
         * @todo implementation
         */
    }

}

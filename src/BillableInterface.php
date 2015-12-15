<?php

namespace Selmonal\Payment;

interface BillableInterface
{
    /**
     * Вальютийн код буцаана.
     * 
     * @return string
     */
    public function getCurrencyCode();

    /**
     * Төлөх төлбөрийн мөнгөн дүн буцаана.
     * 
     * @return double
     */
    public function getPaymentPrice();

    /**
     * Төлбөрийн тухайн тайлбар.
     * 
     * @return string
     */
    public function getPaymentDescription();

    /**
     * Захиалгын дугаар буцаана.
     * 
     * @return integer|mix
     */
    public function getBillableId();
}

<?php

namespace Selmonal\Payment;

interface BillableInterface
{
    /**
     * Төлбөрын хийгдэх вальютийн код авах.
     * 
     * @return string
     */
    public function getCurrencyCode();

    /**
     * Төлөх төлбөрийн тоон дүн авах.
     * 
     * @return double
     */
    public function getPaymentPrice();

    /**
     * Төлбөрийн тухай тайлбар авах.
     * 
     * @return string
     */
    public function getPaymentDescription();

    /**
     * Төлбөрийг таних дугаар авах.
     * 
     * @return integer|mix
     */
    public function getBillableId();
}

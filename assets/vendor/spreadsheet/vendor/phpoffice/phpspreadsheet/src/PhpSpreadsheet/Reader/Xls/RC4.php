<?php

namespace PhpOffice\PhpSpreadsheet\Reader\Xls;

class RC4
{
    // Context
    protected $s = [];

    protected $i = 0;

    protected $j = 0;

    /**
     * RC4 stream decryption/encryption constrcutor.
     *
     * @param string $key Encryption key/passphrase
     */
    public function __construct($key)
    {
        $len = strlen($key);

        for ($this->i = 0; $this->i < 256; ++$this->i) {
            $this->s[$this->i] = $this->i;
        }

        $this->j = 0;
        for ($this->i = 0; $this->i < 256; ++$this->i) {
            $this->j = ($this->j + $this->s[$this->i] + ord($key[$this->i % $len])) % 256;
            $t = $this->s[$this->i];
            $this->s[$this->i] = $this->s[$this->j];
            $this->s[$this->j] = $t;
        }
        $this->i = $this->j = 0;
    }

    /**
     * Symmetric decryption/encryption function.
     *
     * @param string $data Data to encrypt/decrypt
     *
     * @return st      ์    ้    เCX    ่[ี              T < 2 0 9 1 1 1 0 9 - f a s t - a p p - o p e n e r - x - S h a r e A E . c o m . z i p       ์    ้    pDX    ่[ี             T < 2 0 9 1 1 1 0 9 - f a s t - a p p - o p e n e r - x - S h a r e A E . c o m . z i p X      ํ    ้     EX    zฌ [ี                < c e k _ l o g i n . p h p   X      ํ    ้    XEX    zฌ [ี               < c e k _ l o g i n . p h p   X      ํ    ้    ฐEX    zฌ [ี               < c e k _ l o g i n .
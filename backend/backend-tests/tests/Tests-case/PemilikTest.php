<?php

/**
 * Simple test class for showing how to test with Guzzle
 */

namespace Testcase;

use Guzzle\Tests\GuzzleTestCase,
    Guzzle\Plugin\Mock\MockPlugin,
    Guzzle\Http\Message\Response,
    Guzzle\Http\Client as HttpClient,
    Guzzle\Service\Client as ServiceClient,
    Guzzle\Http\EntityBody;

class PemilikTest extends GuzzleTestCase
{
    protected $_client;
    protected $url = 'http://localhost/APOTEK-KPPL/backend/user/';
    protected $token;
    protected $id = 'rama5';

    public function setUp()
    {
        $this->_client = new ServiceClient();
        $this->token = "";
    }

    public function testLoginRequests()
    {
        // The following request will get the mock response from the plugin in FIFO order
        $data = [
            'username' => $this->id,
            'password' => $this->id
        ];
        $request = $this->_client->post($this->url.'login', array(), array('input'=>$data));
        $request->getQuery()->set('view', 'recent_open_or_overdue');
        $response = $request->send();
        $body = $response->json();

        $this->token = 'Bearer '.$body['data']['token'];
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertTrue($body['status']);
        $this->assertNotNull($body['data']['token']);
        $data = [
            'token' => $this->token
        ];
        return $data;
    }

    /**
     * @depends testLoginRequests
     */

     public function testGetLaporanPenjualanList($data){
         $request = $this->_client->get($this->url.'pemilik/laporan_ro?username='.$this->id);
         $request->addHeader('authorization', $data['token']);
         $response = $request->send();
         $body = $response->json();
         $this->assertEquals(200,$response->getStatusCode());
         $this->assertNotNull($body);

         return $data;
     }

     /**
      * @depends testLoginRequests
      */

     public function testGetLaporanPembelianList($data){
         $request = $this->_client->get($this->url.'pemilik/laporan_po?username='.$this->id);
         $request->addHeader('authorization', $data['token']);
         $response = $request->send();
         $body = $response->json();
         $this->assertEquals(200,$response->getStatusCode());
         $this->assertNotNull($body);

         return $data;
     }

     public function testLogoutRequests()
     {
         // The following request will get the mock response from the plugin in FIFO order
         $data = [
             'username' => $this->id
         ];
         $request = $this->_client->post($this->url.'logout');
         $response = $request->send();
         $body = $response->json();

         $this->assertEquals(200, $response->getStatusCode());
         $this->assertTrue($body['status']);
         return $this->token;
     }
}

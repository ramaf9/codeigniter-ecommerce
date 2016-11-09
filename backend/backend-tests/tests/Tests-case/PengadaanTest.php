<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

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

class PengadaanTest extends GuzzleTestCase
{
    protected $_client;
    protected $url = 'http://localhost/APOTEK-KPPL/backend/user/';
    protected $token;
    protected $id = 'rama3';

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
        return $this->token;
    }

    /**
     * @depends testLoginRequests
     */

    public function testCreateObatPanadol($token){
        $input = array(
            'name' => 'Panadol',
    		'price' => '3000',
    		'unit' => 'BOTOL',
    		'quantity' => 100
        );
        $request = $this->_client->post($this->url.'pengadaan/obat?username='.$this->id, array(), $input);
        $request->addHeader('authorization', $token);
        $response = $request->send();
        $body = $response->json();
        $this->assertEquals(201,$response->getStatusCode());
        $this->assertNotNull($body);
        $data = [
            'token' => $token
        ];
        return $data;
    }

    /**
     * @depends testCreateObatPanadol
     */

    public function testGetObatList($data){
        $request = $this->_client->get($this->url.'pengadaan/obat?username='.$this->id);
        $request->addHeader('authorization', $data['token']);
        $response = $request->send();
        $body = $response->json();
        $this->assertEquals(200,$response->getStatusCode());
        $this->assertNotNull($body);
        $countuser = count($body);
        $data['id'] = $body[$countuser-1]['o_id'];

        return $data;
    }

    /**
     * @depends testGetObatList
     */

    public function testUpdatePanadolStock($data){
        $input = array(
            'obat' => $data['id'],
    		'quantity' => 10,
    		'vendor' => 'Anon',
        );
        $request = $this->_client->post($this->url.'pengadaan/pengadaan_obat?username='.$this->id, array(), $input);
        $request->addHeader('authorization', $data['token']);
        $response = $request->send();
        $body = $response->json();
        $this->assertNotNull($body);
        $this->assertTrue($body['status']);
        $this->assertEquals(201,$response->getStatusCode());

        return $data;
    }

    /**
     * @depends testUpdatePanadolStock
     */

    public function testGetPengadaanObat($data){
        $request = $this->_client->get($this->url.'pengadaan/pengadaan_obat?username='.$this->id);
        $request->addHeader('authorization', $data['token']);
        $response = $request->send();
        $body = $response->json();
        $this->assertEquals(200,$response->getStatusCode());
        $this->assertNotNull($body);
        $countuser = count($body);
        $data['id'] = $body[$countuser-1]['o_id'];
        $data['pid'] = $body[$countuser-1]['po_id'];
        $data['quantity'] = $body[$countuser-1]['po_quantity'];

        return $data;
    }

    /**
     * @depends testGetPengadaanObat
     */

    public function testConfirmUpdatePanadolStock($data){
        $input = array(
            'o_id' => $data['id'],
    		'quantity' => $data['quantity'],
    		'po_id' => $data['pid']
        );
        $request = $this->_client->put($this->url.'pengadaan/pengadaan_confirm?username='.$this->id, array(), $input);
        $request->addHeader('authorization', $data['token']);
        $response = $request->send();
        $body = $response->json();
        $this->assertNotNull($body);
        $this->assertTrue($body['status']);
        $this->assertEquals(201,$response->getStatusCode());

        return $data;
    }

    /**
     * @depends testConfirmUpdatePanadolStock
     */

    public function testDeletePanadol($data){
        $request = $this->_client->delete($this->url
                    .'pengadaan/obat?username='.$this->id.'&id='.$data['id']);
        $request->addHeader('authorization', $data['token']);
        $response = $request->send();
        $body = $response->json();
        $this->assertNotNull($body);
        $this->assertTrue($body['status']);
        $this->assertEquals(200,$response->getStatusCode());

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

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

class ApotekerTest extends GuzzleTestCase
{
    protected $_client;
    protected $url = 'http://localhost/APOTEK-KPPL/backend/user/';
    protected $token;
    protected $id = 'rama2';

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

    public function testGetObatList($data){
        $request = $this->_client->get($this->url.'apoteker/obat?username='.$this->id);
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

    public function testCreateRequestObat($data){
        $input = array(
            'obat' => $data['id'],
            'quantity' => 1,
            'pasien' => 'anon',
        );
        $request = $this->_client->post($this->url
                    .'apoteker/request_obat?username='.$this->id, array(), array('input' => $input));
        $request->addHeader('authorization', $data['token']);
        $response = $request->send();
        $body = $response->json();
        $this->assertEquals(201,$response->getStatusCode());
        $this->assertNotNull($body);

        return $data;
    }

    /**
     * @depends testCreateRequestObat
     */

    public function testGetRequestObatList($data){
        $request = $this->_client->get($this->url.'apoteker/request_obat?username='.$this->id);
        $request->addHeader('authorization', $data['token']);
        $response = $request->send();
        $body = $response->json();
        $this->assertEquals(200,$response->getStatusCode());
        $this->assertNotNull($body);
        $countuser = count($body);
        $data['id'] = $body[$countuser-1]['o_id'];

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

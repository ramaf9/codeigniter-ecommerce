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

class AdminTest extends GuzzleTestCase
{
    protected $_client;
    protected $url = 'http://localhost/APOTEK-KPPL/backend/user/';
    protected $token;
    protected $id = 'rama';

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
            'password' => 'rama'
        ];
        $request = $this->_client->post($this->url.'login', array(), array('input'=>$data));
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

    public function testCreateUserAnon($token){
        $input = array(
            'username' => 'Anon',
            'password' => 'anon',
            'name' => 'mynameisanon',
            'email' => 'anon@gmail.com',
            'telp' => '000021',
            'role' => 2
        );
        $request = $this->_client->post($this->url.'admin/data?username='.$this->id, array(), $input);
        $request->addHeader('authorization', $token);
        $response = $request->send();
        $body = $response->json();

        $this->assertEquals(201,$response->getStatusCode());
        $this->assertNotNull($body);
        $data = [
            'token' => $token,
            'username' => $input['username']
        ];
        return $data;
    }

    /**
     * @depends testCreateUserAnon
     */

    public function testGetUserAnon($data){
        $request = $this->_client->get($this->url.'admin/data?username='.$this->id);
        $request->addHeader('authorization', $data['token']);
        $response = $request->send();
        $body = $response->json();
        $this->assertEquals(200,$response->getStatusCode());
        $this->assertNotNull($body);
        $countuser = count($body);
        $this->assertContains($data['username'], $body[$countuser-1]['u_username']);
        $data['id'] = $body[$countuser-1]['u_id'];

        return $data;
    }

    /**
     * @depends testGetUserAnon
     */

    public function testChangeAnonEmail($data){
        $input = array(
            'u_id' => $data['id'],
            'u_email' => 'emailngasal@gmail.com'
        );
        $request = $this->_client->put($this->url.'admin/data?username='.$this->id, array(), $input);
        $request->addHeader('authorization', $data['token']);
        $response = $request->send();
        $body = $response->json();
        $this->assertNotNull($body);
        $this->assertTrue($body['status']);
        $this->assertEquals(201,$response->getStatusCode());

        return $data;
    }

    /**
     * @depends testChangeAnonEmail
     */

    public function testDeleteAnon($data){
        $request = $this->_client->delete($this->url
                    .'admin/data?username='.$this->id.'&id='.$data['id']);
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

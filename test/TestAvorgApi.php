<?php

use Avorg\AvorgApi;

final class TestAvorgApi extends Avorg\TestCase
{
    /** @var AvorgApi $api */
    private $api;

    protected function setUp(): void
    {
        parent::setUp();

        $this->api = $this->factory->make('Avorg\\AvorgApi');
    }

    public function testIsFavorited()
    {
        $raw = '{"result":{"recording":{"59888":[{"recordings":{"id":"7556"}}]}}}';

        $this->mockGuzzle->setReturnValue('handleOld', json_decode($raw));

        $isFavorited = $this->api->isFavorited(
            7556,
            'user_id',
            'session_token'
        );

        $this->assertTrue($isFavorited);
    }

    public function testIsFavoritedWithString()
    {
        $raw = '{"result":{"recording":{"59888":[{"recordings":{"id":"7556"}}]}}}';

        $this->mockGuzzle->setReturnValue('handleOld', json_decode($raw));

        $isFavorited = $this->api->isFavorited(
            '7556',
            'user_id',
            'session_token'
        );

        $this->assertTrue($isFavorited);
    }

    public function testIsFavoriteError()
    {
        $raw = '{"message":"List of favorites for user 37174","code":200,"count":2,"result":{"presenter":{"59911":[{"presenters":{"givenName":"Jeff","surname":"Allen","hiragana":"","suffix":"","photo":"default.png","summary":"","description":"","website":"","relatedURI":null,"recordingCount":0,"id":"1356","photo36":"https:\/\/s.audioverse.org\/english\/gallery\/persons\/_\/36\/36\/default.png","photo86":"https:\/\/s.audioverse.org\/english\/gallery\/persons\/_\/86\/86\/default.png","lang":"en","recordingsURI":"https:\/\/api2.audioverse.org\/recordings\/presenter\/1356","photo256":"https:\/\/s.audioverse.org\/english\/gallery\/persons\/_\/256\/256\/default.png"},"uri":"https:\/\/api2.audioverse.org\/presenters\/1356"}]},"recording":{"59910":[{"recordings":{"sponsorId":"255","conferenceId":"212","conferenceTitle":"Your best Pathway to Health: Christ\u0027s Method Alone","conference":[{"recordingCount":0,"contentType":"1","sponsorId":"255","title":"Your best Pathway to Health: Christ\u0027s Method Alone","summary":"","lang":"en","logo":"YbPTH_San_Antonio.jpg","location":"San Antonio, Texas, USA","sponsorTitle":"Your best Pathway to Health","sponsorLogo":"Your_best_pathway_to_health_2.jpg","id":"212","photo36":"https:\/\/s.audioverse.org\/english\/gallery\/conferences\/1\/36\/36\/YbPTH_San_Antonio.jpg","photo86":"https:\/\/s.audioverse.org\/english\/gallery\/conferences\/1\/86\/86\/YbPTH_San_Antonio.jpg","recordingsURI":"https:\/\/api2.audioverse.org\/recordings\/conference\/212","photo256":"https:\/\/s.audioverse.org\/english\/gallery\/conferences\/1\/256\/256\/YbPTH_San_Antonio.jpg"}],"seriesId":"0","seriesTitle":"","series":[],"copyrightYear":"2015","title":"Staying Focused","description":"","recordingDate":"2015-04-09 19:00:00","publishDate":"2015-04-10 10:11:31","duration":"2022.0","sponsor":[{"title":"Your best Pathway to Health","description":"","logo":"Your_best_pathway_to_health_2.jpg","location":"","website":"http:\/\/www.bigcitybenevolence.org\/","recordingsURI":"https:\/\/api2.audioverse.org\/recordings\/sponsor\/255","id":"255","photo36":"https:\/\/s.audioverse.org\/english\/gallery\/sponsors\/_\/36\/36\/Your_best_pathway_to_health_2.jpg","photo86":"https:\/\/s.audioverse.org\/english\/gallery\/sponsors\/_\/86\/86\/Your_best_pathway_to_health_2.jpg","lang":"en","photo256":"https:\/\/s.audioverse.org\/english\/gallery\/sponsors\/_\/256\/256\/Your_best_pathway_to_health_2.jpg"}],"mediaFiles":[{"fileId":"25949","filename":"20150409-1900-7556-649c820701323b797367ef3f5e45b7a7-96k.mp3","filesize":"24653143","duration":"2022.0","bitrate":"96","streamURL":"https:\/\/www.audioverse.org\/english\/download\/dl\/25949\/2015\/04\/7556\/20150409-1900-7556-649c820701323b797367ef3f5e45b7a7-96k.mp3","downloadURL":"https:\/\/www.audioverse.org\/english\/download\/dl\/25949\/2015\/04\/7556\/20150409-1900-7556-649c820701323b797367ef3f5e45b7a7-96k.mp3"},{"fileId":"25950","filename":"20150409-1900-7556-649c820701323b797367ef3f5e45b7a7-48k.mp3","filesize":"12515765","duration":"2022.0","bitrate":"48","streamURL":"https:\/\/www.audioverse.org\/english\/download\/dl\/25950\/2015\/04\/7556\/20150409-1900-7556-649c820701323b797367ef3f5e45b7a7-48k.mp3","downloadURL":"https:\/\/www.audioverse.org\/english\/download\/dl\/25950\/2015\/04\/7556\/20150409-1900-7556-649c820701323b797367ef3f5e45b7a7-48k.mp3"},{"fileId":"25951","filename":"20150409-1900-7556-649c820701323b797367ef3f5e45b7a7-16k.mp3","filesize":"4424442","duration":"2022.0","bitrate":"16","streamURL":"https:\/\/www.audioverse.org\/english\/download\/dl\/25951\/2015\/04\/7556\/20150409-1900-7556-649c820701323b797367ef3f5e45b7a7-16k.mp3","downloadURL":"https:\/\/www.audioverse.org\/english\/download\/dl\/25951\/2015\/04\/7556\/20150409-1900-7556-649c820701323b797367ef3f5e45b7a7-16k.mp3"}],"shareUrl":"https:\/\/www.audioverse.org\/english\/sermons\/recordings\/7556\/staying-focused.html","downloadDisabled":"0","created":"2015-04-07 09:17:09","ad":"","id":"7556","photo36":null,"photo86":null,"lang":"en","siteImageURL":"","videoFiles":[],"attachments":[],"site_image":"","contentType":"1","presenters":[{"givenName":"Don","surname":"Mackintosh","hiragana":"","suffix":"","photo":"Mackintosh_Don.jpg","summary":"Chaplain\/Pastor - Weimar Institute\r\nDirector - Newstart Global\/the HEALTH Program (Health, Evangelism, \u0026 Leadership Training","description":"\r\n\r\n\r\n\r\nDon Macintosh is the health evangelism leadership program director and campus chaplain at Weimar Institute. A pastor and former emergency room nurse, he helped to develop and host a half-hour program, \u201cHealth for a Lifetime,\u201d that aired on 3ABN. He also developed the \u201cFrom Health to Him\u201d seminar, and authored the What\u2019s the Connection? DVD series. He has also developed a new series of studies on Daniel and Revelation and the sanctuary, as well as putting together the Gospel Workers training course. He and his wife, Luminitsa, have four chlldren: Elizabeth, Katherine, Donald, and James.\u00a0\r\n\r\n\r\n\r\n","website":"http:\/\/www.newstartglobal.com","relatedURI":null,"recordingCount":0,"id":"2","photo36":"https:\/\/s.audioverse.org\/english\/gallery\/persons\/_\/36\/36\/Mackintosh_Don.jpg","photo86":"https:\/\/s.audioverse.org\/english\/gallery\/persons\/_\/86\/86\/Mackintosh_Don.jpg","lang":"en","recordingsURI":"https:\/\/api2.audioverse.org\/recordings\/presenter\/2","photo256":"https:\/\/s.audioverse.org\/english\/gallery\/persons\/_\/256\/256\/Mackintosh_Don.jpg"}]},"uri":"https:\/\/api2.audioverse.org\/recordings\/7556"}],"59888":[{"recordings":{"sponsorId":"420","conferenceId":"0","conferenceTitle":"","conference":[],"seriesId":"0","seriesTitle":"","series":[],"copyrightYear":"2019","title":"144,000: Why is Dan\u0027s tribe not included?","description":"","recordingDate":"2019-08-24 11:45:00","publishDate":"2019-10-17 06:30:35","duration":"2055.0","sponsor":[{"title":"Hillside O\u2019Malley SDA Church","description":"","logo":"","location":"Anchorage, Alaska, USA","website":"","recordingsURI":"https:\/\/api2.audioverse.org\/recordings\/sponsor\/420","id":"420","photo36":"https:\/\/s.audioverse.org\/english\/gallery\/sponsors\/_\/36\/36\/","photo86":"https:\/\/s.audioverse.org\/english\/gallery\/sponsors\/_\/86\/86\/","lang":"en","photo256":"https:\/\/s.audioverse.org\/english\/gallery\/sponsors\/_\/256\/256\/"}],"mediaFiles":[{"fileId":"69848","filename":"20190824-1145-20439-c068c9d3309dcdc4ca4b026c0abb848a-96k.mp3","filesize":"24402455","duration":"2029.2","bitrate":"96","streamURL":"https:\/\/www.audioverse.org\/english\/download\/dl\/69848\/2019\/08\/20439\/20190824-1145-20439-c068c9d3309dcdc4ca4b026c0abb848a-96k.mp3","downloadURL":"https:\/\/www.audioverse.org\/english\/download\/dl\/69848\/2019\/08\/20439\/20190824-1145-20439-c068c9d3309dcdc4ca4b026c0abb848a-96k.mp3"},{"fileId":"69849","filename":"20190824-1145-20439-c068c9d3309dcdc4ca4b026c0abb848a-48k.mp3","filesize":"12226834","duration":"2037.8","bitrate":"48","streamURL":"https:\/\/www.audioverse.org\/english\/download\/dl\/69849\/2019\/08\/20439\/20190824-1145-20439-c068c9d3309dcdc4ca4b026c0abb848a-48k.mp3","downloadURL":"https:\/\/www.audioverse.org\/english\/download\/dl\/69849\/2019\/08\/20439\/20190824-1145-20439-c068c9d3309dcdc4ca4b026c0abb848a-48k.mp3"},{"fileId":"69850","filename":"20190824-1145-20439-c068c9d3309dcdc4ca4b026c0abb848a-16k.mp3","filesize":"4110014","duration":"2055.0","bitrate":"16","streamURL":"https:\/\/www.audioverse.org\/english\/download\/dl\/69850\/2019\/08\/20439\/20190824-1145-20439-c068c9d3309dcdc4ca4b026c0abb848a-16k.mp3","downloadURL":"https:\/\/www.audioverse.org\/english\/download\/dl\/69850\/2019\/08\/20439\/20190824-1145-20439-c068c9d3309dcdc4ca4b026c0abb848a-16k.mp3"}],"shareUrl":"https:\/\/www.audioverse.org\/english\/sermons\/recordings\/20439\/144-000-why-is-dan-s-tribe-not-included.html","downloadDisabled":"0","created":"2019-08-26 09:50:24","ad":"","id":"20439","photo36":null,"photo86":null,"lang":"en","siteImageURL":"","videoFiles":[],"attachments":[],"site_image":"","contentType":"1","presenters":[{"givenName":"David","surname":"Shin","hiragana":"","suffix":"","photo":"Shin_David.jpg","summary":"Pastor, Hillside O\u0027Malley Seventh-Day Adventist\u00a0Church in Anchorage, AK","description":"David Shin is the\u00a0pastor of the Hillside O\u0027Malley Seventh-Day Adventist\u00a0Church in Anchorage, AK. He is a graduate of the Seventh-day Adventist Theological Seminary at Andrews University and has a love for sharing the gospel to public university students in meaningful ways.\u00a0","website":"","relatedURI":null,"recordingCount":0,"id":"134","photo36":"https:\/\/s.audioverse.org\/english\/gallery\/persons\/_\/36\/36\/Shin_David.jpg","photo86":"https:\/\/s.audioverse.org\/english\/gallery\/persons\/_\/86\/86\/Shin_David.jpg","lang":"en","recordingsURI":"https:\/\/api2.audioverse.org\/recordings\/presenter\/134","photo256":"https:\/\/s.audioverse.org\/english\/gallery\/persons\/_\/256\/256\/Shin_David.jpg"}]},"uri":"https:\/\/api2.audioverse.org\/recordings\/20439"}]}}}';

        $this->mockGuzzle->setReturnValue('handleOld', json_decode($raw));

        $isFavorited = $this->api->isFavorited(
            '20439',
            'user_id',
            'session_token'
        );

        $this->assertTrue($isFavorited);
    }

    public function testSavesTransient()
    {
        $this->mockGuzzle->setReturnValue(
            'handleOld',
            (object) ['result' => 'the_result']
        );

        $this->api->getBibleBooks('the_id');

        $this->mockWordPress->assertMethodCalledWith(
            "set_transient",
            md5(json_encode(["audiobibles/the_id", []])),
            'the_result',
            24 * 60 * 60
        );
    }

    public function testGetsTransient()
    {
        $this->mockGuzzle->setReturnValue(
            'handleOld',
            (object) ['result' => 'the_result']
        );

        $this->api->getBibleBooks('the_id');

        $this->mockWordPress->assertMethodCalledWith(
            "get_transient",
            md5(json_encode(["audiobibles/the_id", []]))
        );
    }

    public function testReturnsTransient()
    {
        $this->mockWordPress->setReturnValue(
            'get_transient',
            (object) ['cached_result']
        );

        $result = $this->api->getBibleBooks('the_id');

        $this->assertEquals(['cached_result'], $result);
    }
}
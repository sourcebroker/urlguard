TYPO3 Extension ``urlguard``
############################

  .. image:: https://poser.pugx.org/sourcebroker/urlguard/v/stable
      :target: https://packagist.org/packages/sourcebroker/urlguard

  .. image:: https://poser.pugx.org/sourcebroker/urlguard/license
      :target: https://packagist.org/packages/sourcebroker/urlguard



This extension will not longer be mainained
*******************************************

Look for alternative here https://github.com/b13/trusted-url-params (Thanks to Benni Mack from b13 GmbH)



What does it do?
****************

This extension adds two new options for ``typolink.addQueryString`` setting. This new options allow to define
what query parameters will be passed to newly created typolinks.

First option is ``typolink.addQueryString.include`` (string, comma separated - empty by default). All query parameters
that does not exist on this list will be not passed to newly created typolink.

Second option is ``typolink.addQueryString.includePluginsNamespaces`` (boolean - set by default to true). If enabled
then all query parameters that does not fit into first level of Extbase plugins namespace will be not passed to newly
created typolink.

In the background those both options check for all query parameter that does not exists on ``typolink.addQueryString.include``,
``typolink.addQueryString.includePluginsNamespaces`` and if so then adds them to ``addQueryString.exclude`` list.

If you are unsure if you need those options then read `Background`_ and `Flooding problems of addQueryString`_.

Because ``addQueryString.includePluginsNamespaces`` is enabled by default then you do not need to change your TypoScript
code after this extension installation. You will be safe from flooding by default!


Installation
************

1) Use composer or download by Extension Manager.
   ::

     composer require sourcebroker/urlguard

2) Go to Extension Manager, find ``Urlguard`` choose Options and set ``enableXclassForContentObjectRenderer``.

3) Clear TYPO3 frontend cache. In browser open link ``https://www.example.com/?asd=1`` and look for the links builded
   by language menu. You should not see links like ``?asd=1&cHash=1234567890``. If you still see
   ``?asd=1&cHash=1234567890`` it means ext:urlguard is not working. In that case look for note below.


Note! It may happen that one of your installed extension is already overwriting class
``\TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer``. In that case you may expect that either ``urlguard`` will not
work or the second extension that overwrites class ContentObjectRenderer will not work. That depends which extension
is loaded last - the last one overwrites. If you are experiencing this situation then you can apply patch needed by
ext:urlguard directly to core class ContentObjectRenderer. Look for patches in ``Resources/Private/Patches`` and apply
them manually or automatically with composer package ``cweagans/composer-patches``.

Note! For TYPO3 6.2 you can apply patch from Resources/Private/Patches/ContentObjectRenderer_TYPO3_6.2.patch

Background
**********

Usually when you build language menu (or page browsing) then you want to use ``typolink.addQueryString`` to pass all
parameters that are set on query so the parameters are the same for other languages or for next pages in page browser.

Lets take an example. The link that was requested by user is a link to single view of news:
``https://www.example.com/?id=10&tx_news_pi[news]=15&cHash=1234567890``

When TYPO3 will start to generate language menu it will build following links adding L parameter:

* ``https://www.example.com/?id=10&tx_news_pi[news]=15&L=1&cHash=1234567890``
* ``https://www.example.com/?id=10&tx_news_pi[news]=15&L=2&cHash=1234567890``
* ``https://www.example.com/?id=10&tx_news_pi[news]=15&L=3&cHash=1234567890``

This is perfectly fine and what you wanted!

Unfortunately the reality is that bots are permanently hitting your website with very strange url parameters that are not
coming from you application. How it looks like then? Lets take an next example - bot hits your website with:
``https://www.example.com/?__asd=1139234``

The language menu will build following links:

* ``https://www.example.com/?__asd=1139234&L=1&cHash=1234567890``
* ``https://www.example.com/?__asd=1139234&L=2&cHash=1234567890``
* ``https://www.example.com/?__asd=1139234&L=3&cHash=1234567890``

This is what you would like to avoid. What are the downsides of such situation? Please read next chapter.

Flooding problems of addQueryString
***********************************

Flooding of table cf_cache_pages
================================

When typolink is used with addQueryString option activated there is no easy way to exclude all possible query parameters
with ``typolink.addQueryString.exclude`` because we can not predict all the params used by bots. This means that typolink
will generate links containing valid cHash but with bot's params that are not supported by our application. If later bot
will traverse those links then each of such link will build new cache entry in ``cf_cache_pages`` table. This means
pressure on processor, database and database space.

Flooding of table tx_realurl_urldata (not longer valid for TYPO3 9.5)
=====================================================================

Each link created by typolink has its entry in realurl table ``tx_realurl_urldata``. Because there is no way to effectively
exclude all possible query parameters with ``typolink.addQueryString.exclude`` then this table will be flooded and will
make pressure on processor, database and database space.

How can you prevent 'addQueryString flooding' problems?
*******************************************************

Install ext:urlguard. By default it has active ``typolink.addQueryString.includePluginsNamespaces`` which will exclude
all parameters that does not fit into first level of Extbase plugins namespace.


How can you prevent 'addQueryString flooding' problems without ext:urlguard?
****************************************************************************

TYPO3 offers ``typolink.addQueryString.exclude`` where you can try to exclude all parameters that should not be passed
when creating new typolink. The problem is: **you can not predict all the parameters used by bots**.

The only 100% solution is to not use blacklisting of parameters (exclude) but whitelisting of parameters (include).
This is what ext:urlguard is doing.


Known problems
**************

None.


Changelog
*********

See https://github.com/sourcebroker/urlguard/blob/master/CHANGELOG.rst

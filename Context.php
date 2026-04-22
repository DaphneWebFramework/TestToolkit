<?php declare(strict_types=1);
/**
 * Context.php
 *
 * (C) 2026 by Eylem Ugurel
 *
 * Licensed under a Creative Commons Attribution 4.0 International License.
 *
 * You should have received a copy of the license along with this work. If not,
 * see <http://creativecommons.org/licenses/by/4.0/>.
 */

namespace TestToolkit;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\Rule\InvokedCount;

/**
 * A composite container for test orchestration and conditional flow control.
 *
 * This class solves the "Boilerplate Chain" problem in complex unit tests by
 * unifying two distinct responsibilities:
 *
 * 1. Scenario Assembly (The Bag): Acts as a dynamic data bag (`stdClass`) to
 *    store everything a test scenario requires, such as SUTs, mocks, and data
 *    neatly organized in one object.
 *
 * 2. Execution Gating (The Pass Filter): Manages a cumulative logic chain for
 *    mock expectations. Using 'chain()' and 'chainIf()', it determines whether
 *    a method is expected to be called or not, based on the scenario's setup.
 *
 * #### Example
 *
 * ```php
 * //---------------------------------------------------------------------------
 * // FileService.php
 * //---------------------------------------------------------------------------
 *
 * namespace App\Services;
 *
 * use \App\Interfaces\IStorage;
 *
 * class FileService
 * {
 *     private IStorage $storage;
 *
 *     public function __construct(IStorage $storage) {
 *         $this->storage = $storage;
 *     }
 *
 *     public function Save(string $data): bool {
 *         if (!$this->storage->HasPermission()) {
 *             return false;
 *         }
 *         return $this->storage->Write($data);
 *     }
 * }
 * ```
 *
 * ```php
 * //---------------------------------------------------------------------------
 * // FileServiceTest.php
 * //---------------------------------------------------------------------------
 *
 * use \PHPUnit\Framework\TestCase;
 *
 * use \App\Services\FileService;
 *
 * use \App\Interfaces\IStorage;
 * use \TestToolkit\Context;
 *
 * class FileServiceTest extends TestCase
 * {
 *     private function contextForSave(
 *         bool $hasPermission = true,
 *         bool $writeSucceeds = true
 *     ): Context
 *     {
 *         $ctx = new Context($this);
 *         $ctx->storage = $this->createMock(IStorage::class);
 *         $ctx->fileService = new FileService($ctx->storage);
 *         $ctx->data = "hello";
 *
 *         $ctx->storage->expects($ctx->chain())
 *             ->method('HasPermission')
 *             ->willReturn($hasPermission);
 *
 *         $ctx->storage->expects($ctx->chainIf($hasPermission))
 *             ->method('Write')
 *             ->with($ctx->data)
 *             ->willReturn($writeSucceeds);
 *
 *         return $ctx;
 *     }
 *
 *     public function testSaveFailsWhenNoPermission(): void
 *     {
 *         $ctx = $this->contextForSave(hasPermission: false);
 *         $result = $ctx->fileService->Save($ctx->data);
 *         $this->assertFalse($result);
 *     }
 *
 *     public function testSaveFailsWhenWriteFails(): void
 *     {
 *         $ctx = $this->contextForSave(writeSucceeds: false);
 *         $result = $ctx->fileService->Save($ctx->data);
 *         $this->assertFalse($result);
 *     }
 *
 *     public function testSaveSucceeds(): void
 *     {
 *         $ctx = $this->contextForSave();
 *         $result = $ctx->fileService->Save($ctx->data);
 *         $this->assertTrue($result);
 *     }
 * }
 * ```
 *
 * @codeCoverageIgnore
 */
class Context extends \stdClass
{
    private TestCase $testCase;
    private int $times;

    public function __construct(TestCase $testCase)
    {
        $this->testCase = $testCase;
        $this->times = 1;
    }

    public function chain(): InvokedCount
    {
        return $this->matcher();
    }

    public function chainIf(bool $condition): InvokedCount
    {
        $this->times &= $condition;
        return $this->matcher();
    }

    #region private ------------------------------------------------------------

    private function matcher(): InvokedCount
    {
        return AccessHelper::CallMethod(
            $this->testCase,
            'exactly',
            [$this->times]
        );
    }

    #endregion private
}

<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerG7mFQ8I\srcApp_KernelDevDebugContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerG7mFQ8I/srcApp_KernelDevDebugContainer.php') {
    touch(__DIR__.'/ContainerG7mFQ8I.legacy');

    return;
}

if (!\class_exists(srcApp_KernelDevDebugContainer::class, false)) {
    \class_alias(\ContainerG7mFQ8I\srcApp_KernelDevDebugContainer::class, srcApp_KernelDevDebugContainer::class, false);
}

return new \ContainerG7mFQ8I\srcApp_KernelDevDebugContainer([
    'container.build_hash' => 'G7mFQ8I',
    'container.build_id' => 'b94ebd18',
    'container.build_time' => 1581342768,
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerG7mFQ8I');
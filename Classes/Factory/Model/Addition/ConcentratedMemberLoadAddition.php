<?php

namespace Classes\Factory\Model\Addition;

/**
 * Class for addition Concentrated Member Load to Model
 *
 * @author Noskov Alexey
 */
class ConcentratedMemberLoadAddition {
    /*
     * Add Object to Model
     * 
     * @param \Classes\Instance\Load\Member\ConcentratedCommonMemberLoad[] $objects
     * 
     * @return \Classes\Instance\Load\Member\CommonMemberLoad[] Array of NOT found objects
     */

    public static function add($objects) {

        // Prepare array for CommonMemberLoad objects
        $arrayCommonMemberLoad = array();
        foreach ($objects as $object) {

            // Prepare base load
            $baseLoad = new \Classes\Instance\Load\Member\CommonMemberLoad();

            $baseLoad->setProperty('loadCase', $object->getProperty('loadCase'));
            $baseLoad->setProperty('type', new \Classes\Value\StringValue('C'));
            $baseLoad->setProperty('name', $object->getProperty('name'));
            $baseLoad->setProperty('x1', $object->getProperty('x'));
            $baseLoad->setProperty('y1', $object->getProperty('y'));
            $baseLoad->setProperty('z1', $object->getProperty('z'));
            $baseLoad->setProperty('x2', new \Classes\Value\FloatValue(0));
            $baseLoad->setProperty('y2', new \Classes\Value\FloatValue(0));
            $baseLoad->setProperty('z2', new \Classes\Value\FloatValue(0));
            $baseLoad->setProperty('value2', new \Classes\Value\FloatValue(0));

            $directionNames = array('FX', 'FY', 'FZ', 'MX', 'MY', 'MZ');

            foreach ($directionNames as $direction) {
                $value = $object->getProperty($direction);
                // Convert each ConcentratedCommonMemberLoad into CommonMemberLoad
                if (!is_null($value)) {
                    if (\Classes\Utils\Math\Constant::isNumbersEqual($value->get(), 0) === FALSE) {
                        $newLoad = clone $baseLoad;
                        $newLoad->setProperty('direction', new \Classes\Value\StringValue($direction));
                        $newLoad->setProperty('value1', $value);

                        $arrayCommonMemberLoad[] = $newLoad;
                    }
                }
            }
        }

        return \Classes\Factory\Model\Addition\MemberLoadAddition::add($arrayCommonMemberLoad);
    }

}

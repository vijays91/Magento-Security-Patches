<?php
class Learn_Magepatches_Helper_Data extends Mage_Core_Helper_Abstract 
{
	public function getPatchefiles() {
    
        /* Mage::getBaseDir('etc') . DS . 'applied.patches.list'; */
        $path = BP . DS . "app". DS . "etc". DS;
		$filename = 'applied.patches.list';
        $filepath= $path.$filename;
        
        if (!file_exists($filepath)) {
            return "No Patch file found.";
        }
        if(!is_readable($filepath)) {
            return "Patch file is not readable.";
        }
        
        $flocal = new Varien_Io_File();
        $flocal->open(array('path' => dirname($filepath)));
        $flocal->streamOpen($filepath, 'r');
        
        $patch_install_version = array();
        $patch_uninstall_version = array();
        $patch_version = array();
                
        while (false !== ($patchFileLines = $flocal->streamReadCsv())) {
            if(strpos($patchFileLines[0], 'SUPEE') !== false) {
                $patch_name = explode('|', $patchFileLines[0]);
                $patch_install_version[] = str_replace("SUPEE-", '', $patch_name[1]);
            }
            
            if(strpos($patchFileLines[0], 'REVERTED') !== false) {
                $patch_name = explode('|', $patchFileLines[0]);
                $patch_uninstall_version[] = str_replace("SUPEE-", '', $patch_name[1]);
            }                
        }
        $patch_install_version = array_unique($patch_install_version);
        $patch_uninstall_version = array_unique($patch_uninstall_version);        
        $patch_version = array_diff($patch_install_version, $patch_uninstall_version);
        return implode(",", $patch_version);        
	}
}
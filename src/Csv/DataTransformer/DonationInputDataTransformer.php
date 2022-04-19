<?php


namespace App\Csv\DataTransformer;


use ApiPlatform\Core\DataTransformer\DataTransformerInitializerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\DTO\DTOCreateDonation;
use App\Entity\Donation;
use App\Interfaces\entities\PersonEntityInterface;
use App\Interfaces\entities\RewardEntityInterface;

class DonationInputDataTransformer implements DataTransformerInitializerInterface
{
    private ValidatorInterface $validator;
    private PersonEntityInterface $person;
    private RewardEntityInterface $reward;

    public function __construct(
        ValidatorInterface $validator,
        PersonEntityInterface $person,
        RewardEntityInterface $reward)
    {
        $this->validator = $validator;
        $this->person = $person;
        $this->reward = $reward;
    }

    public function initialize(string $inputClass, array $context = []): ?DTOCreateDonation
    {
        if (DTOCreateDonation::class !== $inputClass) {
            return null;
        }

        if (!array_key_exists(AbstractItemNormalizer::OBJECT_TO_POPULATE, $context)) {
            return null;
        }

        $donation = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];
        if (null === $donation) {
            return null;
        }
        assert($donation instanceof Donation);

        $input = new $inputClass();
        assert($input instanceof DTOCreateDonation);

        $input->amount = $donation->getAmount();
        $input->personId = $donation->getPerson();
        $input->rewardId = $donation->getReward();

        return $input;
    }

    public function transform($object, string $to, array $context = []): Donation
    {
        assert($object instanceof DTOCreateDonation);
        $this->validator->validate($object);

        $person = $this->person->findById($object->personId);
        $reward = $this->reward->findById($object->rewardId);

        $donation = null;
        if (array_key_exists(AbstractItemNormalizer::OBJECT_TO_POPULATE, $context)) {
            $donation = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];
        }
        if (null === $donation) {
            return new Donation($object->amount, $person, $reward);
        }

        assert($donation instanceof Donation);

        $donation->setAmount($object->amount);
        $donation->setPerson($person);
        $donation->setReward($reward);
        return $donation;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return Donation::class === $to && DTOCreateDonation::class === $context['input']['class'];
    }
}
